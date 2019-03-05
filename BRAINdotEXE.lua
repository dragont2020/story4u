local Addons = {"Ahri","Brand","Darius","DrMundo","Garen","KogMaw","Lux","MasterYi","Pyke","Quinn","Talon","Udyr","Urgot","Xayah","XinZhao"}
local Started = false
function OnLoad()
	if table.contains(Addons,myHero.charName) and Started == false then
		_G[myHero.charName]()
		Started = true
	end
end

local LocalAlly                     = myHero.team
local LocalJungle                   = 300
local LocalEnemy                    = LocalJungle - LocalAlly

local _atan                         = math.atan2
local _min                          = math.min
local _abs                          = math.abs
local _sqrt                         = math.sqrt
local _floor                        = math.floor
local _max                          = math.max
local _huge                         = math.huge
local _pi                           = math.pi
local _insert                       = table.insert
local _sort                         = table.sort
local _pairs                        = pairs
local _find                         = string.find
local _sub                          = string.sub
local _len                          = string.len

local LocalDrawLine					= Draw.Line;
local LocalDrawColor				= Draw.Color;
local LocalDrawCircle				= Draw.Circle;
local LocalDrawCircleMinimap        = Draw.CircleMinimap;
local LocalDrawText					= Draw.Text;
local LocalControlIsKeyDown			= Control.IsKeyDown;
local LocalControlMouseEvent		= Control.mouse_event;
local LocalControlSetCursorPos		= Control.SetCursorPos;
local LocalControlCastSpell         = Control.CastSpell;
local LocalControlKeyUp				= Control.KeyUp;
local LocalControlKeyDown			= Control.KeyDown;
local LocalControlMove              = Control.Move;
local LocalGetTickCount             = GetTickCount;
local LocalGamecursorPos            = Game.cursorPos;
local LocalGameCanUseSpell			= Game.CanUseSpell;
local LocalGameLatency				= Game.Latency;
local LocalGameTimer				= Game.Timer;
local LocalGameHeroCount 			= Game.HeroCount;
local LocalGameHero 				= Game.Hero;
local LocalGameMinionCount 			= Game.MinionCount;
local LocalGameMinion 				= Game.Minion;
local LocalGameTurretCount 			= Game.TurretCount;
local LocalGameTurret 				= Game.Turret;
local LocalGameWardCount 			= Game.WardCount;
local LocalGameWard 				= Game.Ward;
local LocalGameObjectCount 			= Game.ObjectCount;
local LocalGameObject				= Game.Object;
local LocalGameMissileCount 		= Game.MissileCount;
local LocalGameMissile				= Game.Missile;
local LocalGameParticleCount 		= Game.ParticleCount;
local LocalGameParticle				= Game.Particle;
local LocalGameIsChatOpen			= Game.IsChatOpen;
local LocalGameIsOnTop				= Game.IsOnTop;

require "Brainlib"

local castSpell = {state = 0, tick = LocalGetTickCount(), casting = LocalGetTickCount() - 1000, mouse = mousePos}
function CastSpell(spell,pos,range,delay)
    local range = range or _huge
    local delay = delay or 250
    local ticker = LocalGetTickCount()

	if castSpell.state == 0 and GetDistance(myHero.pos,pos) < range and ticker - castSpell.casting > delay + LocalGameLatency() and pos:ToScreen().onScreen then
		castSpell.state = 1
		castSpell.mouse = mousePos
		castSpell.tick = ticker
	end
	if castSpell.state == 1 then
		if ticker - castSpell.tick < LocalGameLatency() then
			LocalControlSetCursorPos(pos)
			LocalControlKeyDown(spell)
			LocalControlKeyUp(spell)
			castSpell.casting = ticker + delay
			DelayAction(function()
				if castSpell.state == 1 then
					LocalControlSetCursorPos(castSpell.mouse)
					castSpell.state = 0
				end
			end,0.01)
		end
		if ticker - castSpell.casting > LocalGameLatency() then
			LocalControlSetCursorPos(castSpell.mouse)
			castSpell.state = 0
		end
	end
end

function ReleaseSpell(spell,pos,range,delay)
    local delay = delay or 250
    local ticker = LocalGetTickCount()
	if castSpell.state == 0 and GetDistance(myHero.pos,pos) < range and ticker - castSpell.casting > delay + LocalGameLatency() then
		castSpell.state = 1
		castSpell.mouse = mousePos
		castSpell.tick = ticker
	end
	if castSpell.state == 1 then
		if ticker - castSpell.tick < LocalGameLatency() then
			if not pos:ToScreen().onScreen then
				pos = myHero.pos + Vector(myHero.pos,pos):Normalized() * _random(530,760)
				LocalControlSetCursorPos(pos)
				LocalControlKeyUp(spell)
			else
				LocalControlSetCursorPos(pos)
				LocalControlKeyUp(spell)
			end
			castSpell.casting = ticker + delay
			DelayAction(function()
				if castSpell.state == 1 then
					LocalControlSetCursorPos(castSpell.mouse)
					castSpell.state = 0
				end
			end,LocalGameLatency()/1000)
		end
		if ticker - castSpell.casting > LocalGameLatency() then
			LocalControlSetCursorPos(castSpell.mouse)
			castSpell.state = 0
		end
	end
end

class "Ahri"

function Ahri:__init()
	print("Brain.exe - Ahri loaded")
	self.Q = { delay = 0.25, speed = 2500,  radius = 100, range = 880 }
	self.E = { delay = 0.25, speed = 1550,  radius = 60,  range = 975 }
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function Ahri:Menu()
	BDEahri = MenuElement({id = "BDEahri", name = "Brain.exe - Ahri", type = MENU})
	
	BDEahri:MenuElement({id = "Script", name = "Script", type = MENU})
	BDEahri:MenuElement({id = "Drawings", name = "Drawings", type = MENU})
	BDEahri:MenuElement({id = "Prediction", name = "Hitchance Manager", type = MENU})

	BDEahri.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDEahri.Script:MenuElement({id = "useW", name = "Use W", value = true})
    BDEahri.Script:MenuElement({id = "useE", name = "Use E", value = true})

	BDEahri.Drawings:MenuElement({id = "drawQ", name = "Draw Q", value = true})
	BDEahri.Drawings:MenuElement({id = "drawW", name = "Draw W", value = true})
    BDEahri.Drawings:MenuElement({id = "drawE", name = "Draw E", value = true})
	
	BDEahri.Prediction:MenuElement({id = "QhitChance", name = "Q", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
	BDEahri.Prediction:MenuElement({id = "EhitChance", name = "E", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
end

function Ahri:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
end

function Ahri:Combo()
	if BDEahri.Script.useE:Value() then
		self:Elogic()
	end
	if BDEahri.Script.useQ:Value() then
		self:Qlogic()
	end
	if BDEahri.Script.useW:Value() then
		self:Wlogic()
	end
end

function Ahri:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.Q.range,"AP")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.Q.range, self.Q.delay, self.Q.speed, self.Q.radius, false)
			if hitChance and hitChance >= BDEahri.Prediction.QhitChance:Value() then
				self:CastQ(target,aimPosition)
			end
		end
	end
end

function Ahri:CastQ(target,QcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,QcastPos) <= self.Q.range then
			LocalControlCastSpell(HK_Q,QcastPos)
		end
	end
end

function Ahri:Wlogic()
	if LocalGameCanUseSpell(_W) == 0 and castSpell.state == 0 then
		local target = GetTarget(700,"AP")
		if target then
			LocalControlCastSpell(HK_W)
		end
	end
end

function Ahri:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.E.range,"AP")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.E.range, self.E.delay, self.E.speed, self.E.radius, true)
			if hitChance and hitChance >= BDEahri.Prediction.EhitChance:Value() then
				if GetMinionCollision(myHero.pos, aimPosition, self.E.radius) == 0 then
					self:CastE(target,aimPosition)
				end
			end
		end
	end
end

function Ahri:CastE(target,EcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,EcastPos) <= self.E.range then
			LocalControlCastSpell(HK_E,EcastPos)
		end
	end
end

function Ahri:Draw()
	if myHero.dead then return end
	if BDEahri.Drawings.drawQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, self.Q.range, 3, LocalDrawColor(255,000,000,255))
	end
	if BDEahri.Drawings.drawW:Value() and LocalGameCanUseSpell(_W) == 0 then
		LocalDrawCircle(myHero.pos, 700, 3, LocalDrawColor(255,000,255,000))
	end
	if BDEahri.Drawings.drawE:Value() and LocalGameCanUseSpell(_E) == 0 then
		LocalDrawCircle(myHero.pos, self.E.range, 3, LocalDrawColor(255,255,255,000))
	end
end


class "Brand"

function Brand:__init()
	print("Brain.exe - Brand loaded")
	self.Q = { delay = 0.25,  speed = 1600,  radius = 60,  range = 1050 }
	self.W = { delay = 0.625, speed = _huge, radius = 250, range = 900  }
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function Brand:Menu()
	BDEbrand = MenuElement({id = "BDEbrand", name = "Brain.exe - Brand", type = MENU})
	
	BDEbrand:MenuElement({id = "Script", name = "Script", type = MENU})
	BDEbrand:MenuElement({id = "Drawings", name = "Drawings", type = MENU})
	BDEbrand:MenuElement({id = "Prediction", name = "Hitchance Manager", type = MENU})

	BDEbrand.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDEbrand.Script:MenuElement({id = "ablazeQ", name = "Ablazed Only", value = true})
	BDEbrand.Script:MenuElement({id = "useW", name = "Use W", value = true})
	BDEbrand.Script:MenuElement({id = "useE", name = "Use E", value = true})
	BDEbrand.Script:MenuElement({id = "useR", name = "Use R", value = true})
	BDEbrand.Script:MenuElement({id = "countR", name = "Min Count R", value = 3, min = 1, max = 5})

	BDEbrand.Drawings:MenuElement({id = "drawQ", name = "Draw Q", value = true})
	BDEbrand.Drawings:MenuElement({id = "drawW", name = "Draw W", value = true})
	BDEbrand.Drawings:MenuElement({id = "drawE", name = "Draw E", value = true})
	BDEbrand.Drawings:MenuElement({id = "drawR", name = "Draw R", value = true})
	
	BDEbrand.Prediction:MenuElement({id = "QhitChance", name = "Q", value = 1, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
	BDEbrand.Prediction:MenuElement({id = "WhitChance", name = "W", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
end

function Brand:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
end

function Brand:Combo()
	if BDEbrand.Script.useR:Value() then
		self:Rlogic()
	end
	if BDEbrand.Script.useW:Value() then
		self:Wlogic()
	end
	if BDEbrand.Script.useQ:Value() then
		self:Qlogic()
	end
	if BDEbrand.Script.useE:Value() then
		self:Elogic()
	end
end

function Brand:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.Q.range,"AP")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.Q.range, self.Q.delay, self.Q.speed, self.Q.radius, true)
			if hitChance and hitChance >= BDEbrand.Prediction.QhitChance:Value() then
				local BrandBuff = GetBuffData(target,"BrandAblaze")
				if BDEbrand.Script.ablazeQ:Value() and BrandBuff.count == 0 then return end
				if GetMinionCollision(myHero.pos, aimPosition, self.Q.radius) == 0 then
					self:CastQ(target,aimPosition)
				end
			end
		end
	end
end

function Brand:CastQ(target,QcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,QcastPos) <= self.Q.range then
			LocalControlCastSpell(HK_Q,QcastPos)
		end
	end
end

function Brand:Wlogic()
	if LocalGameCanUseSpell(_W) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.W.range,"AP")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.W.range, self.W.delay, self.W.speed, self.W.radius, false)
			if hitChance and hitChance >= BDEbrand.Prediction.WhitChance:Value() then
				self:CastW(target,aimPosition)
			end
		end
	end
end

function Brand:CastW(target,WcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,WcastPos) <= self.W.range then
			CastSpell(HK_W,WcastPos,self.W.range)
		end
	end
end

function Brand:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(625,"AP")
		if target then
			LocalControlCastSpell(HK_E,target)
		end
	end
end

function Brand:Rlogic()
	if LocalGameCanUseSpell(_R) == 0 and castSpell.state == 0 then
		local target = GetTarget(750,"AP")
		if target then
			if HeroesAround(650, target.pos, LocalEnemy) >= BDEbrand.Script.countR:Value() then
				if HeroesAround(650, target.pos, LocalEnemy) < 2 and MinionsAround(650, target.pos, LocalEnemy) < 3 then return end
				LocalControlCastSpell(HK_R,target)
			end
		end
	end
end

function Brand:Draw()
	if myHero.dead then return end
	if BDEbrand.Drawings.drawQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, self.Q.range, 3, LocalDrawColor(255,000,000,255))
	end
	if BDEbrand.Drawings.drawW:Value() and LocalGameCanUseSpell(_W) == 0 then
		LocalDrawCircle(myHero.pos, self.W.range, 3, LocalDrawColor(255,000,255,000))
	end
	if BDEbrand.Drawings.drawE:Value() and LocalGameCanUseSpell(_E) == 0 then
		LocalDrawCircle(myHero.pos, 625, 3, LocalDrawColor(255,255,255,000))
	end
	if BDEbrand.Drawings.drawR:Value() and LocalGameCanUseSpell(_R) == 0 then
		LocalDrawCircle(myHero.pos, 750, 3, LocalDrawColor(255,255,000,000))
	end
end


class "Darius"

function Darius:__init()
	print("Brain.exe - Darius loaded")
	self.E = { delay = 0.25, speed = _huge, radius = 125, range = 535  }
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function Darius:Menu()
	BDEdarius = MenuElement({id = "BDEdarius", name = "Brain.exe - Darius", type = MENU})
	
	BDEdarius:MenuElement({id = "Script", name = "Script", type = MENU})
	BDEdarius:MenuElement({id = "Drawings", name = "Drawings", type = MENU})
	BDEdarius:MenuElement({id = "Prediction", name = "Hitchance Manager", type = MENU})

	BDEdarius.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDEdarius.Script:MenuElement({id = "useW", name = "Use W", value = true})
	BDEdarius.Script:MenuElement({id = "useE", name = "Use E", value = true})
	BDEdarius.Script:MenuElement({id = "useR", name = "Use R", value = true})

	BDEdarius.Drawings:MenuElement({id = "drawQ", name = "Draw Q", value = true})
	BDEdarius.Drawings:MenuElement({id = "drawE", name = "Draw E", value = true})
	BDEdarius.Drawings:MenuElement({id = "drawR", name = "Draw R", value = true})
	
	BDEdarius.Prediction:MenuElement({id = "EhitChance", name = "E", value = 1, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
end

function Darius:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
	if BDEdarius.Script.useR:Value() then
		self:Rlogic()
	end
end

function Darius:Combo()
	if myHero.attackData.state == STATE_WINDDOWN then
		if BDEdarius.Script.useW:Value() then
			self:Wlogic()
		end
	end
	if BDEdarius.Script.useQ:Value() then
		self:Qlogic()
	end
	if BDEdarius.Script.useE:Value() then
		self:Elogic()
	end
end

function Darius:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(425,"AD")
		if target then
			LocalControlCastSpell(HK_Q)
		end
	end
end

function Darius:Wlogic()
	if LocalGameCanUseSpell(_W) == 0 and castSpell.state == 0 then
		local target = GetTarget(250,"AD")
		if target then
			LocalControlCastSpell(HK_W)
		end
	end
end

function Darius:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.E.range,"AD")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.E.range, self.E.delay, self.E.speed, self.E.radius, false)
			if hitChance and hitChance >= BDEdarius.Prediction.EhitChance:Value() then
				self:CastE(target,aimPosition)
			end
		end
	end
end

function Darius:CastE(target,EcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,EcastPos) <= self.E.range then
			LocalControlCastSpell(HK_E,EcastPos)
		end
	end
end

function Darius:Rlogic()
	if LocalGameCanUseSpell(_R) == 0 and castSpell.state == 0 then
		for i = 1, LocalGameHeroCount() do
        	local unit = LocalGameHero(i)
			if unit and unit.isEnemy and unit.IsValidTarget and not unit.dead and not unit.isImmortal and unit.isTargetable then
				local Rlevel = myHero:GetSpellData(_R).level
				local BaseRdamage = ({100,200,300})[Rlevel]
				local multiplier = myHero.bonusDamage * 0.75
				local bonus = ({20,40,60})[Rlevel] + myHero.bonusDamage * 0.15
				local passive = GetBuffData(unit,"DariusHemo")
				local damageBonus = passive.count * bonus
				local Rdmg = BaseRdamage + multiplier + damageBonus
				if Rdmg >= unit.health and GetDistance(myHero.pos,unit.pos) < 460 then
					LocalControlCastSpell(HK_R,unit)
				end
			end
        end
    end
end

function Darius:Draw()
	if myHero.dead then return end
	if BDEdarius.Drawings.drawQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, 425, 3, LocalDrawColor(255,000,000,255))
	end
	if BDEdarius.Drawings.drawE:Value() and LocalGameCanUseSpell(_E) == 0 then
		LocalDrawCircle(myHero.pos, self.E.range, 3, LocalDrawColor(255,255,255,000))
	end
	if BDEdarius.Drawings.drawR:Value() and LocalGameCanUseSpell(_R) == 0 then
		LocalDrawCircle(myHero.pos, 460, 3, LocalDrawColor(255,255,000,000))
	end
end


class "DrMundo"

function DrMundo:__init()
	print("Brain.exe - DrMundo loaded")
	self.Q = { delay = 0.25, speed = 2000, radius = 60, range = 975  }
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function DrMundo:Menu()
	BDEdrmundo = MenuElement({id = "BDEdrmundo", name = "Brain.exe - Dr. Mundo", type = MENU})
	
	BDEdrmundo:MenuElement({id = "Script", name = "Script", type = MENU})
	BDEdrmundo:MenuElement({id = "Drawings", name = "Drawings", type = MENU})
	BDEdrmundo:MenuElement({id = "Prediction", name = "Hitchance Manager", type = MENU})

	BDEdrmundo.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDEdrmundo.Script:MenuElement({id = "useW", name = "Use W", value = true})
	BDEdrmundo.Script:MenuElement({id = "useE", name = "Use E", value = true})

	BDEdrmundo.Drawings:MenuElement({id = "drawQ", name = "Draw Q", value = true})
	
	BDEdrmundo.Prediction:MenuElement({id = "QhitChance", name = "Q", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
end

function DrMundo:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
end

function DrMundo:Combo()
	if myHero.attackData.state == STATE_WINDDOWN then
		if BDEdrmundo.Script.useE:Value() then
			self:Elogic()
		end
	end
	if BDEdrmundo.Script.useW:Value() then
		self:Wlogic()
	end
	if BDEdrmundo.Script.useQ:Value() then
		self:Qlogic()
	end
end

function DrMundo:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.Q.range,"AP")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.Q.range, self.Q.delay, self.Q.speed, self.Q.radius, false)
			if hitChance and hitChance >= BDEdrmundo.Prediction.QhitChance:Value() then
				if GetMinionCollision(myHero.pos, aimPosition, self.Q.radius) == 0 then
					self:CastQ(target,aimPosition)
				end
			end
		end
	end
end

function DrMundo:CastQ(target,QcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,QcastPos) <= self.Q.range then
			LocalControlCastSpell(HK_Q,QcastPos)
		end
	end
end

function DrMundo:Wlogic()
	if LocalGameCanUseSpell(_W) == 0 and castSpell.state == 0 then
		local target = GetTarget(325,"AP")
		if target and myHero:GetSpellData(_W).toggleState ~= 2 then
			LocalControlCastSpell(HK_W)
		end
	end
end

function DrMundo:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(250,"AD")
		if target then
			LocalControlCastSpell(HK_E)
		end
	end
end

function DrMundo:Draw()
	if myHero.dead then return end
	if BDEdrmundo.Drawings.drawQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, self.Q.range, 3, LocalDrawColor(255,000,000,255))
	end
end


class "Garen"

function Garen:__init()
	print("Brain.exe - Garen loaded")
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function Garen:Menu()
	BDEgaren = MenuElement({id = "BDEgaren", name = "Brain.exe - Garen", type = MENU})
	
	BDEgaren:MenuElement({id = "Script", name = "Script", type = MENU})
	BDEgaren:MenuElement({id = "Drawings", name = "Drawings", type = MENU})

	BDEgaren.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDEgaren.Script:MenuElement({id = "useE", name = "Use E", value = true})
	BDEgaren.Script:MenuElement({id = "useR", name = "Use R", value = true})

	BDEgaren.Drawings:MenuElement({id = "drawE", name = "Draw E", value = true})
	BDEgaren.Drawings:MenuElement({id = "drawR", name = "Draw R", value = true})
end

function Garen:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
	if BDEgaren.Script.useR:Value() then
		self:Rlogic()
	end
	if myHero:GetSpellData(_E).toggleState == 2 then
		SetAttacks(false)
	else
		SetAttacks(true)
	end
end

function Garen:Combo()
	if myHero.attackData.state == STATE_WINDDOWN then
		if BDEgaren.Script.useQ:Value() then
			self:Qlogic()
		end
	end
	if BDEgaren.Script.useE:Value() then
		self:Elogic()
	end
end

function Garen:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(250,"AD")
		if target then
			LocalControlCastSpell(HK_Q)
		end
	end
end

function Garen:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(325,"AD")
		if target and myHero:GetSpellData(_E).toggleState ~= 2 then
			LocalControlCastSpell(HK_E)
		end
	end
end

function Garen:Rlogic()
	if LocalGameCanUseSpell(_R) == 0 and castSpell.state == 0 then
		for i = 1, LocalGameHeroCount() do
        	local unit = LocalGameHero(i)
			if unit and unit.isEnemy and unit.IsValidTarget and not unit.dead and not unit.isImmortal and unit.isTargetable then
				local Rlevel = myHero:GetSpellData(_R).level
				local BaseRdamage = ({175,350,525})[Rlevel]
				local missingHealth = unit.maxHealth - unit.health
				local multiplier = ({0.143,0.166,0.2})[Rlevel]
				local passive = GetBuffData(unit,"garenpassiveenemytarget")
				local Rdmg = BaseRdamage + (multiplier * missingHealth)
				if GetDistance(myHero.pos,unit.pos) < 400 then
					if passive.count >= 1 and Rdmg >= unit.health then
						LocalControlCastSpell(HK_R,unit)
					elseif passive.count == 0 then
						local Mdmg = CalcMagicalDamage(myHero,unit,Rdmg)
						if Mdmg >= unit.health then
							LocalControlCastSpell(HK_R,unit)
						end
					end
				end
			end
        end
    end
end

function Garen:Draw()
	if myHero.dead then return end
	if BDEgaren.Drawings.drawE:Value() and LocalGameCanUseSpell(_E) == 0 then
		LocalDrawCircle(myHero.pos, 325, 3, LocalDrawColor(255,255,255,000))
	end
	if BDEgaren.Drawings.drawR:Value() and LocalGameCanUseSpell(_R) == 0 then
		LocalDrawCircle(myHero.pos, 400, 3, LocalDrawColor(255,255,000,000))
	end
end


class "KogMaw"

function KogMaw:__init()
	print("Brain.exe - KogMaw loaded")
	self.Q = { delay = 0.25, speed = 1650, radius = 70, range = 1175 }
	self.E = { delay = 0.25, speed = 1400, radius = 90, range = 1280 }
	self.R = { delay = 0.85, speed = _huge, radius = 170, range = self:Rrange() }
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function KogMaw:Rrange()
	if myHero:GetSpellData(_R).level == 1 then
		return 1200
	elseif myHero:GetSpellData(_R).level == 2 then
		return 1500
	elseif myHero:GetSpellData(_R).level == 3 then
		return 1800
	end
end

function KogMaw:Menu()
	BDEkogmaw = MenuElement({id = "BDEkogmaw", name = "Brain.exe - KogMaw", type = MENU})
	
	BDEkogmaw:MenuElement({id = "Script", name = "Script", type = MENU})
	BDEkogmaw:MenuElement({id = "Drawings", name = "Drawings", type = MENU})
	BDEkogmaw:MenuElement({id = "Prediction", name = "Hitchance Manager", type = MENU})

	BDEkogmaw.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDEkogmaw.Script:MenuElement({id = "useW", name = "Use W", value = true})
	BDEkogmaw.Script:MenuElement({id = "useE", name = "Use E", value = true})
	BDEkogmaw.Script:MenuElement({id = "useR", name = "Use R", value = true})
	BDEkogmaw.Script:MenuElement({id = "maxR", name = "Max R Stacks", value = 2, min = 0, max = 10})

	BDEkogmaw.Drawings:MenuElement({id = "drawQ", name = "Draw Q", value = true})
	BDEkogmaw.Drawings:MenuElement({id = "drawE", name = "Draw E", value = true})
	BDEkogmaw.Drawings:MenuElement({id = "drawR", name = "Draw R", value = true})
	
	BDEkogmaw.Prediction:MenuElement({id = "QhitChance", name = "Q", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
	BDEkogmaw.Prediction:MenuElement({id = "EhitChance", name = "E", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
	BDEkogmaw.Prediction:MenuElement({id = "RhitChance", name = "R", value = 3, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
end

function KogMaw:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
end

function KogMaw:Combo()
	if BDEkogmaw.Script.useR:Value() then
		self:Rlogic()
	end
	if BDEkogmaw.Script.useW:Value() then
		self:Wlogic()
	end
	if BDEkogmaw.Script.useQ:Value() then
		self:Qlogic()
	end
	if BDEkogmaw.Script.useE:Value() then
		self:Elogic()
	end
end

function KogMaw:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.Q.range,"AP")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.Q.range, self.Q.delay, self.Q.speed, self.Q.radius, false)
			if hitChance and hitChance >= BDEkogmaw.Prediction.QhitChance:Value() then
				if GetMinionCollision(myHero.pos, aimPosition, self.Q.radius) == 0 then
					self:CastQ(target,aimPosition)
				end
			end
		end
	end
end

function KogMaw:CastQ(target,QcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,QcastPos) <= self.Q.range then
			LocalControlCastSpell(HK_Q,QcastPos)
		end
	end
end

function KogMaw:Wlogic()
	if LocalGameCanUseSpell(_W) == 0 and castSpell.state == 0 then
		local Wrange = 610 + (20 * myHero:GetSpellData(_W).level)
		local target = GetTarget(Wrange,"AP")
		if target then
			LocalControlCastSpell(HK_W)
		end
	end
end

function KogMaw:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.E.range,"AP")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.E.range, self.E.delay, self.E.speed, self.E.radius, false)
			if hitChance and hitChance >= BDEkogmaw.Prediction.EhitChance:Value() then
				self:CastE(target,aimPosition)
			end
		end
	end
end

function KogMaw:CastE(target,EcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,EcastPos) <= self.E.range then
			LocalControlCastSpell(HK_E,EcastPos)
		end
	end
end

function KogMaw:Rlogic()
	if LocalGameCanUseSpell(_R) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.R.range,"AP")
		if target and GetDistance(myHero.pos,target.pos) > (myHero.range + 200) then
			local stacks = GotBuff(target,"kogmawlivingartillerycost")
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.R.range, self.R.delay, self.R.speed, self.R.radius, false)
			if hitChance and hitChance >= BDEkogmaw.Prediction.RhitChance:Value() and stacks <= BDEkogmaw.Script.maxR:Value() then
				self:CastR(target,aimPosition)
			end
		end
	end
end

function KogMaw:CastR(target,RcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,RcastPos) <= self.R.range then
			LocalControlCastSpell(HK_R,RcastPos)
		end
	end
end

function KogMaw:Draw()
	if myHero.dead then return end
	if BDEkogmaw.Drawings.drawQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, self.Q.range, 3, LocalDrawColor(255,000,000,255))
	end
	if BDEkogmaw.Drawings.drawE:Value() and LocalGameCanUseSpell(_E) == 0 then
		LocalDrawCircle(myHero.pos, self.E.range, 3, LocalDrawColor(255,255,255,000))
	end
	if BDEkogmaw.Drawings.drawR:Value() and LocalGameCanUseSpell(_R) == 0 then
		LocalDrawCircle(myHero.pos, self.R.range, 3, LocalDrawColor(255,255,000,000))
	end
end


class "Lux"

function Lux:__init()
	print("Brain.exe - Lux loaded")
	self.Q = { delay = 0.25, speed = 1200,  radius = 50,  range = 1175 }
	self.E = { delay = 0.25, speed = 1200,  radius = 310, range = 1000 }
	self.R = { delay = 1,    speed = _huge, radius = 120, range = 3340 }
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function Lux:Menu()
	BDElux = MenuElement({id = "BDElux", name = "Brain.exe - Lux", type = MENU})
	
	BDElux:MenuElement({id = "Script", name = "Script", type = MENU})
	BDElux:MenuElement({id = "Drawings", name = "Drawings", type = MENU})
	BDElux:MenuElement({id = "Prediction", name = "Hitchance Manager", type = MENU})

	BDElux.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDElux.Script:MenuElement({id = "useE", name = "Use E", value = true})
	BDElux.Script:MenuElement({id = "useR", name = "Use R", value = true})

	BDElux.Drawings:MenuElement({id = "drawQ", name = "Draw Q", value = true})
	BDElux.Drawings:MenuElement({id = "drawE", name = "Draw E", value = true})
	BDElux.Drawings:MenuElement({id = "drawR", name = "Draw R", value = true})
	
	BDElux.Prediction:MenuElement({id = "QhitChance", name = "Q", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
	BDElux.Prediction:MenuElement({id = "EhitChance", name = "E", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
	BDElux.Prediction:MenuElement({id = "RhitChance", name = "R", value = 3, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
end

function Lux:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
	if BDElux.Script.useR:Value() then
		self:Rlogic()
	end
	if myHero:GetSpellData(_E).toggleState == 2 then
		LocalControlCastSpell(HK_E)
	end
end

function Lux:Combo()
	if BDElux.Script.useE:Value() and myHero:GetSpellData(_E).toggleState ~= 2 then
		self:Elogic()
	end
	if BDElux.Script.useQ:Value() then
		self:Qlogic()
	end
end

function Lux:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.Q.range,"AP")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.Q.range, self.Q.delay, self.Q.speed, self.Q.radius, true)
			if hitChance and hitChance >= BDElux.Prediction.QhitChance:Value() then
				if GetMinionCollision(myHero.pos, aimPosition, self.Q.radius) <= 1 then
					self:CastQ(target,aimPosition)
				end
			end
		end
	end
end

function Lux:CastQ(target,QcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,QcastPos) <= self.Q.range then
			LocalControlCastSpell(HK_Q,QcastPos)
		end
	end
end

function Lux:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.E.range,"AP")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.E.range, self.E.delay, self.E.speed, self.E.radius, false)
			if hitChance and hitChance >= BDElux.Prediction.EhitChance:Value() then
				self:CastE(target,aimPosition)
			end
		end
	end
end

function Lux:CastE(target,EcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,EcastPos) <= self.E.range then
			LocalControlCastSpell(HK_E,EcastPos)
		end
	end
end

function Lux:Rlogic()
	if LocalGameCanUseSpell(_R) == 0 and castSpell.state == 0 then
		for i = 1, LocalGameHeroCount() do
        	local unit = LocalGameHero(i)
			if unit and unit.isEnemy and unit.IsValidTarget and not unit.dead and not unit.isImmortal and unit.isTargetable then
				local Rlevel = myHero:GetSpellData(_R).level
				local BaseRdamage = ({300,400,500})[Rlevel]
				local multiplier = 0.75 * myHero.ap
				local Rdmg = BaseRdamage + multiplier
				local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, unit, self.R.range, self.R.delay, self.R.speed, self.R.radius, false)
				if CalcMagicalDamage(myHero,unit,Rdmg) > unit.health and hitChance and hitChance >= BDElux.Prediction.RhitChance:Value() then
					self:CastR(unit,aimPosition)
				end
			end
        end
    end
end

function Lux:CastR(target,RcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,RcastPos) <= self.R.range then
			LocalControlCastSpell(HK_R,RcastPos)
		end
	end
end

function Lux:Draw()
	if myHero.dead then return end
	if BDElux.Drawings.drawQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, self.Q.range, 3, LocalDrawColor(255,000,000,255))
	end
	if BDElux.Drawings.drawE:Value() and LocalGameCanUseSpell(_E) == 0 then
		LocalDrawCircle(myHero.pos, self.E.range, 3, LocalDrawColor(255,255,255,000))
	end
	if BDElux.Drawings.drawR:Value() and LocalGameCanUseSpell(_R) == 0 then
		LocalDrawCircleMinimap(myHero.pos, self.R.range, 3, LocalDrawColor(255,255,000,000))
	end
end


class "MasterYi"

function MasterYi:__init()
	print("Brain.exe - MasterYi loaded")
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function MasterYi:Menu()
	BDEmasteryi = MenuElement({id = "BDEmasteryi", name = "Brain.exe - Master Yi", type = MENU})
	
	BDEmasteryi:MenuElement({id = "Script", name = "Script", type = MENU})
	BDEmasteryi:MenuElement({id = "Drawings", name = "Drawings", type = MENU})

	BDEmasteryi.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDEmasteryi.Script:MenuElement({id = "useW", name = "Use W", value = true})
	BDEmasteryi.Script:MenuElement({id = "useE", name = "Use E", value = true})

	BDEmasteryi.Drawings:MenuElement({id = "drawQ", name = "Draw Q", value = true})
end

function MasterYi:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if myHero:GetSpellData(_W).toggleState == 2 then
		LocalControlMove(LocalGamecursorPos())
	end
	if GetMode() == "Combo" then
		self:Combo()
	end
	if GetMode() == "Harass" then
		self:Harass()
	end
end

function MasterYi:Combo()
	if BDEmasteryi.Script.useQ:Value() then
		self:Qlogic()
	end
	if myHero.attackData.state == STATE_WINDDOWN then
		if BDEmasteryi.Script.useW:Value() then
			self:Wlogic()
		end
	end
	if BDEmasteryi.Script.useE:Value() then
		self:Elogic()
	end
end

function MasterYi:Harass()
	if myHero.attackData.state == STATE_WINDDOWN then
		if BDEmasteryi.Script.useW:Value() then
			self:Wlogic()
		end
	end
	if BDEmasteryi.Script.useE:Value() then
		self:Elogic()
	end
end

function MasterYi:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(600,"AD")
		if target then
			LocalControlCastSpell(HK_Q,target)
		end
	end
end

function MasterYi:Wlogic()
	if LocalGameCanUseSpell(_W) == 0 and castSpell.state == 0 then
		local target = GetTarget(250,"AD")
		if target then
			LocalControlCastSpell(HK_W)
		end
	end
end

function MasterYi:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(250,"AD")
		if target then
			LocalControlCastSpell(HK_E)
		end
	end
end

function MasterYi:Draw()
	if myHero.dead then return end
	if BDEmasteryi.Drawings.drawQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, 600, 3, LocalDrawColor(255,000,000,255))
	end
end


class "Pyke"

function Pyke:__init()
	print("Brain.exe - Pyke loaded")
	self.Q = { delay = 0.2,  speed = 2000,  radius = 70,  range = 400 }
	self.E = { delay = 0.25, speed = 1700,  radius = 150, range = 550 }
    self.R = { delay = 0.5,  speed = _huge, radius = 150, range = 750 }
	self.Qchannel = false
	self.Qtimer = LocalGameTimer()
	self.Rtimer = LocalGameTimer()
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function Pyke:Menu()
	BDEpyke = MenuElement({id = "BDEpyke", name = "Brain.exe - Pyke", type = MENU})
	
	BDEpyke:MenuElement({id = "Script", name = "Script", type = MENU})
	BDEpyke:MenuElement({id = "Killsteal", name = "Killsteal", type = MENU})
	BDEpyke:MenuElement({id = "Drawings", name = "Drawings", type = MENU})
	BDEpyke:MenuElement({id = "Prediction", name = "Hitchance Manager", type = MENU})

	BDEpyke.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
    BDEpyke.Script:MenuElement({id = "useE", name = "Use E", value = true})
    BDEpyke.Script:MenuElement({id = "order", name = "Skill Order", value = 1, drop = {"Q first","E first"}})
    BDEpyke.Script:MenuElement({id = "useR", name = "Use R", value = true})

	BDEpyke.Drawings:MenuElement({id = "drawQ", name = "Draw Q", value = false})
	BDEpyke.Drawings:MenuElement({id = "drawmaxQ", name = "Draw max Q", value = true})
    BDEpyke.Drawings:MenuElement({id = "drawE", name = "Draw E", value = true})
	BDEpyke.Drawings:MenuElement({id = "drawR", name = "Draw R", value = true})
	
	BDEpyke.Prediction:MenuElement({id = "QhitChance", name = "Q", value = 1, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
	BDEpyke.Prediction:MenuElement({id = "EhitChance", name = "E", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
	BDEpyke.Prediction:MenuElement({id = "RhitChance", name = "R", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
end

function Pyke:Tick()
	if Game.IsChatOpen() then return end
	if self.Qchannel then
		SetAttacks(false)
	else
		SetAttacks(true)
	end
	self:Qmanager()
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
	if BDEpyke.Script.useR:Value() then
		self:Rlogic()
	end
end

function Pyke:Combo()
	if BDEpyke.Script.useE:Value() then
		self:Elogic()
	end
	if BDEpyke.Script.useQ:Value() then
		self:Qlogic()
	end
end

function Pyke:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(1100,"AD")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, 1100, 0.25 + LocalGameLatency()/1000, 2000, 70, true)
			local hitChance2, aimPosition2 = HPred:GetHitchance(myHero.pos, target, 1100, 1, 2000, 70, false)
			if hitChance and hitChance2 then
				if GetDistance(myHero.pos,aimPosition2) < 1200 then
					self:TriggerQ(target)
				end
				if LocalGameTimer() - self.Qtimer > 0.5 then
					if hitChance >= BDEpyke.Prediction.QhitChance:Value() and self.Qchannel == true then
						if GetMinionCollision(myHero.pos, aimPosition, self.Q.radius) == 0 then
							self:ReleaseQ(target,aimPosition)
						end
					end
				end
			end
		end
	end
end

function Pyke:TriggerQ(target)
	local trigger = true
	if LocalGameCanUseSpell(_Q) == 0 and self.Qchannel == false and trigger == true then
		LocalControlKeyDown(HK_Q)
	end
end

function Pyke:ReleaseQ(target,QcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (((LocalGameTimer() - OnWaypoint(target).time < 0.15 or LocalGameTimer() - OnWaypoint(target).time > 1.0) and OnVision(target).state == true) or (OnVision(target).state == false)) and GetDistance(myHero.pos,QcastPos) < self.Q.range - target.boundingRadius then
		ReleaseSpell(HK_Q,QcastPos,self.Q.range,100)
	end
end

function Pyke:Qmanager()
	if self.Qchannel == true then
		self.Q.range = 400 + (LocalGameTimer() - (self.Qtimer + 0.5)) * 1400
		if self.Q.range > 1100 then self.Q.range = 1100 end
	end
	local QchannelBuff = GetBuffData(myHero,"PykeQ")
	if self.Qchannel == false and QchannelBuff.count > 0 then
		self.Qtimer = LocalGameTimer()
		self.Qchannel = true
	end
	if self.Qchannel == true and QchannelBuff.count == 0 then
		self.Qchannel = false
		if LocalControlIsKeyDown(HK_Q) == true then
			self.Q.range = 400
			LocalControlKeyUp(HK_Q)
		end
	end
	if LocalControlIsKeyDown(HK_Q) == true and self.Qchannel == false then
		DelayAction(function()
			if LocalControlIsKeyDown(HK_Q) == true and self.Qchannel == false then
				self.Q.range = 400
				LocalControlKeyUp(HK_Q)
			end
		end,0.3)
	end
	if LocalControlIsKeyDown(HK_Q) == true and LocalGameCanUseSpell(_Q) ~= 0 then
		DelayAction(function()
			if LocalControlIsKeyDown(HK_Q) == true then
				self.Q.range = 400
				LocalControlKeyUp(HK_Q)
			end
		end,0.01)
	end
end

function Pyke:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and self.Qchannel == false and castSpell.state == 0 then
		local Qtarget = GetTarget(1100,"AD")
		if Qtarget and BDEpyke.Script.useQ:Value() and LocalGameCanUseSpell(_Q) == 0 and GetDistance(Qtarget.pos,myHero.pos) < 1100 and Qtarget:GetCollision(self.Q.radius, self.Q.speed, self.Q.delay) == 0 then return end
		local target = GetTarget(self.E.range,"AD")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.E.range, self.E.delay, self.E.speed, self.E.radius, false)
			if hitChance and hitChance >= BDEpyke.Prediction.EhitChance:Value() then
				self:CastE(target,aimPosition)
			end
		end
	end
end

function Pyke:CastE(target,EcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,EcastPos) <= 550 then
			LocalControlCastSpell(HK_E,EcastPos)
		end
	end
end

function Pyke:Rlogic()
	if LocalGameCanUseSpell(_R) == 0 and self.Qchannel == false and castSpell.state == 0 and LocalGameTimer() - self.Rtimer > 0.3 then
		for i = 1, LocalGameHeroCount() do
        	local unit = LocalGameHero(i)
			if unit.isEnemy and unit.IsValidTarget and not unit.dead and not unit.isImmortal and unit.isTargetable then
				local myLevel = myHero.levelData.lvl
				local BaseRdamage = ({0,0,0,0,0,190,240,290,340,390,440,475,510,545,580,615,635,655})[myLevel]
				local multiplier = myHero.bonusDamage * 0.6
				local Rdmg = BaseRdamage + multiplier
				local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, unit, self.R.range, self.E.delay, self.E.speed, self.E.radius, false)
				if hitChance and hitChance >= BDEpyke.Prediction.RhitChance:Value() and Rdmg >= unit.health then
					self:CastR(unit,aimPosition)
				end
			end
        end
    end
end

function Pyke:CastR(target,RcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and GetDistance(myHero.pos,RcastPos) < self.R.range then
		LocalControlCastSpell(HK_R,RcastPos)
		self.Rtimer = LocalGameTimer()
	end
end

function Pyke:Draw()
	if myHero.dead then return end
	if BDEpyke.Drawings.drawQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, 400, 3, LocalDrawColor(255,000,000,255))
	end
	if BDEpyke.Drawings.drawmaxQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, 1100, 3, LocalDrawColor(255,000,000,255))
	end
	if BDEpyke.Drawings.drawE:Value() and LocalGameCanUseSpell(_E) == 0 then
		LocalDrawCircle(myHero.pos, self.E.range, 3, LocalDrawColor(255,000,255,000))
	end
	if BDEpyke.Drawings.drawR:Value() and LocalGameCanUseSpell(_R) == 0 then
		LocalDrawCircle(myHero.pos, self.R.range, 3, LocalDrawColor(255,255,000,000))
	end
end


class "Quinn"

function Quinn:__init()
	print("Brain.exe - Quinn loaded")
	self.Q = { delay = 0.25, speed = 1550, radius = 60, range = 1025  }
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function Quinn:Menu()
	BDEquinn = MenuElement({id = "BDEquinn", name = "Brain.exe - Quinn", type = MENU})
	
	BDEquinn:MenuElement({id = "Script", name = "Script", type = MENU})
	BDEquinn:MenuElement({id = "Drawings", name = "Drawings", type = MENU})
	BDEquinn:MenuElement({id = "Prediction", name = "Hitchance Manager", type = MENU})

	BDEquinn.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDEquinn.Script:MenuElement({id = "useE", name = "Use E", value = true})

	BDEquinn.Drawings:MenuElement({id = "drawQ", name = "Draw Q", value = true})
	BDEquinn.Drawings:MenuElement({id = "drawE", name = "Draw E", value = true})
	
	BDEquinn.Prediction:MenuElement({id = "QhitChance", name = "Q", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
end

function Quinn:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
end

function Quinn:Combo()
	if BDEquinn.Script.useE:Value() then
		self:Elogic()
	end
	if BDEquinn.Script.useQ:Value() then
		self:Qlogic()
	end
end

function Quinn:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.Q.range,"AD")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.Q.range, self.Q.delay, self.Q.speed, self.Q.radius, false)
			if hitChance and hitChance >= BDEquinn.Prediction.QhitChance:Value() then
				self:CastQ(target,aimPosition)
			end
		end
	end
end

function Quinn:CastQ(target,QcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,QcastPos) <= self.Q.range then
			LocalControlCastSpell(HK_Q,QcastPos)
		end
	end
end

function Quinn:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(675,"AD")
		if target then
			LocalControlCastSpell(HK_E,target)
		end
	end
end

function Quinn:Draw()
	if myHero.dead then return end
	if BDEquinn.Drawings.drawQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, self.Q.range, 3, LocalDrawColor(255,000,000,255))
	end
	if BDEquinn.Drawings.drawE:Value() and LocalGameCanUseSpell(_E) == 0 then
		LocalDrawCircle(myHero.pos, 675, 3, LocalDrawColor(255,255,255,000))
	end
end


class "Talon"

function Talon:__init()
	print("Brain.exe - Talon loaded")
	self.W = { delay = 0.25, speed = 1850, radius = 35, range = 650  }
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function Talon:Menu()
	BDEtalon = MenuElement({id = "BDEtalon", name = "Brain.exe - Talon", type = MENU})
	
	BDEtalon:MenuElement({id = "Script", name = "Script", type = MENU})
	BDEtalon:MenuElement({id = "Drawings", name = "Drawings", type = MENU})
	BDEtalon:MenuElement({id = "Prediction", name = "Hitchance Manager", type = MENU})

	BDEtalon.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDEtalon.Script:MenuElement({id = "useW", name = "Use W", value = true})

	BDEtalon.Drawings:MenuElement({id = "drawQ", name = "Draw Q", value = true})
	BDEtalon.Drawings:MenuElement({id = "drawW", name = "Draw W", value = true})
	BDEtalon.Drawings:MenuElement({id = "drawR", name = "Draw R", value = true})
	
	BDEtalon.Prediction:MenuElement({id = "WhitChance", name = "W", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
end

function Talon:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
	if GetMode() == "Harass" then
		self:Harass()
	end
end

function Talon:Combo()
	if BDEtalon.Script.useQ:Value() then
		self:Qlogic()
	end
	if BDEtalon.Script.useW:Value() then
		self:Wlogic()
	end
end

function Talon:Harass()
	if BDEtalon.Script.useW:Value() then
		self:Wlogic()
	end
end

function Talon:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(575,"AD")
		if target then
			LocalControlCastSpell(HK_Q,target)
		end
	end
end

function Talon:Wlogic()
	if LocalGameCanUseSpell(_W) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.W.range,"AD")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.W.range, self.W.delay, self.W.speed, self.W.radius, false)
			if hitChance and hitChance >= BDEtalon.Prediction.WhitChance:Value() then
				self:CastW(target,aimPosition)
			end
		end
	end
end

function Talon:CastW(target,WcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,WcastPos) <= self.W.range then
			LocalControlCastSpell(HK_W,WcastPos)
		end
	end
end

function Talon:Draw()
	if myHero.dead then return end
	if BDEtalon.Drawings.drawQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, 575, 3, LocalDrawColor(255,000,000,255))
	end
	if BDEtalon.Drawings.drawW:Value() and LocalGameCanUseSpell(_W) == 0 then
		LocalDrawCircle(myHero.pos, self.W.range, 3, LocalDrawColor(255,000,255,000))
	end
	if BDEtalon.Drawings.drawR:Value() and LocalGameCanUseSpell(_R) == 0 then
		LocalDrawCircle(myHero.pos, 550, 3, LocalDrawColor(255,255,000,000))
	end
end


class "Udyr"

function Udyr:__init()
	print("Brain.exe - Udyr loaded")
	self:Menu()
	function OnTick() self:Tick() end
end

function Udyr:Menu()
	BDEudyr = MenuElement({id = "BDEudyr", name = "Brain.exe - Udyr", type = MENU})
	
	BDEudyr:MenuElement({id = "Script", name = "Script", type = MENU})

	BDEudyr.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDEudyr.Script:MenuElement({id = "useW", name = "Use W", value = true})
	BDEudyr.Script:MenuElement({id = "healthW", name = "Under %", value = 20, min = 0, max = 100})
	BDEudyr.Script:MenuElement({id = "useE", name = "Use E", value = true})
end

function Udyr:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
end

function Udyr:Combo()
	if BDEudyr.Script.useE:Value() then
		self:Elogic()
	end
	if BDEudyr.Script.useQ:Value() then
		self:Qlogic()
	end
	if BDEudyr.Script.useW:Value() then
		self:Wlogic()
	end
end

function Udyr:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(250,"AD")
		if target then
			local stunCheck = GetBuffData(target,"udyrbearstuncheck")
			if stunCheck.count ~= 0 then
				LocalControlCastSpell(HK_Q)
			end
		end
	end
end

function Udyr:Wlogic()
	if LocalGameCanUseSpell(_W) == 0 and castSpell.state == 0 and GetPercentHP(myHero) <= BDEudyr.Script.healthW:Value() then
		if LocalGameCanUseSpell(_Q) == 0 and BDEudyr.Script.useQ:Value() then return end
		local target = GetTarget(250,"AD")
		if target then
			local stunCheck = GetBuffData(target,"udyrbearstuncheck")
			if stunCheck.count ~= 0 then
				LocalControlCastSpell(HK_W)
			end
		end
	end
end

function Udyr:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(250,"AD")
		if target then
			local stunCheck = GetBuffData(target,"udyrbearstuncheck")
			if stunCheck.count == 0 then
				LocalControlCastSpell(HK_E)
			end
		else
			LocalControlCastSpell(HK_E)
		end
	end
end


class "Urgot"

function Urgot:__init()
	print("Brain.exe - Urgot loaded")
	self.Q = {charName="Urgot",slot=_Q,type="circular",speed=math.huge,range=800,delay=0.6,radius=215,hitbox=true,aoe=true,cc=true,collision=false};
	self.W = {range=490};
    self.E = {charName="Urgot",slot=_E,type="linear",speed=1050,range=475,delay=0.45,radius=100,hitbox=true,aoe=true,cc=true,collision=true};
	self.R = {charName="Urgot",slot=_R,type="linear",speed=3200,range=1600,delay=0.4,radius=80,hitbox=true,aoe=false,cc=true,collision=false};
    self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function Urgot:Menu()
	BDEurgot = MenuElement({id = "BDEurgot", name = "Brain.exe - Urgot", type = MENU})
	
	BDEurgot:MenuElement({id = "Script", name = "Script", type = MENU})
	BDEurgot:MenuElement({id = "Drawings", name = "Drawings", type = MENU})
	BDEurgot:MenuElement({id = "Prediction", name = "Hitchance Manager", type = MENU})

	BDEurgot.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDEurgot.Script:MenuElement({id = "useW", name = "Use W", value = true})
	BDEurgot.Script:MenuElement({id = "useE", name = "Use E", value = true})
	BDEurgot.Script:MenuElement({id = "useR", name = "Use R", value = true})

	BDEurgot.Drawings:MenuElement({id = "drawQ", name = "Draw Q", value = true})
	BDEurgot.Drawings:MenuElement({id = "drawW", name = "Draw W", value = true})
	BDEurgot.Drawings:MenuElement({id = "drawE", name = "Draw E", value = true})
	BDEurgot.Drawings:MenuElement({id = "drawR", name = "Draw R", value = true})
	
	BDEurgot.Prediction:MenuElement({id = "QhitChance", name = "Q", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
	BDEurgot.Prediction:MenuElement({id = "EhitChance", name = "E", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
    BDEurgot.Prediction:MenuElement({id = "RhitChance", name = "R", value = 3, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
end

function Urgot:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if myHero:GetSpellData(_R).toggleState == 2 then
		LocalControlCastSpell(HK_R)
	end
	if BDEurgot.Script.useR:Value() then
		self:Rlogic()
	end
	if GetMode() == "Combo" then
		self:Combo()
	end
	if GetMode() == "Harass" then
		self:Harass()
	end
	if myHero:GetSpellData(_W).toggleState == 2 then
		SetAttacks(false)
	else
		SetAttacks(true)
	end
end

function Urgot:Combo()
	if BDEurgot.Script.useW:Value() and myHero:GetSpellData(_W).toggleState ~= 2 then
		self:Wlogic()
	end
	if BDEurgot.Script.useQ:Value() then
		self:Qlogic()
	end
	if BDEurgot.Script.useE:Value() then
		self:Elogic()
	end
end

function Urgot:Harass()
	if BDEurgot.Script.useW:Value() then
		self:Wlogic()
	end
	if BDEurgot.Script.useQ:Value() then
		self:Qlogic()
	end
end

function Urgot:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.Q.range,"AD")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.Q.range, self.Q.delay, self.Q.speed, self.Q.radius, false)
			if hitChance and hitChance >= BDEurgot.Prediction.QhitChance:Value() then
				self:CastQ(target,aimPosition)
			end
		end
	end
end

function Urgot:CastQ(target,QcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,QcastPos) <= self.Q.range then
			CastSpell(HK_Q,QcastPos,self.Q.range)
		end
	end
end

function Urgot:Wlogic()
	if LocalGameCanUseSpell(_W) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.W.range,"AD")
		if target then
			LocalControlCastSpell(HK_W)
		end
	end
end

function Urgot:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.E.range,"AD")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.E.range, self.E.delay, self.E.speed, self.E.radius, false)
			if hitChance and hitChance >= BDEurgot.Prediction.EhitChance:Value() then
				if GetMinionCollision(myHero.pos, aimPosition, self.E.radius) == 0 then
					self:CastE(target,aimPosition)
				end
			end
		end
	end
end

function Urgot:CastE(target,EcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,EcastPos) <= self.E.range then
			LocalControlCastSpell(HK_E,EcastPos)
		end
	end
end

function Urgot:Rlogic()
	if LocalGameCanUseSpell(_R) == 0 and castSpell.state == 0 and myHero:GetSpellData(_R).toggleState ~= 2 then
		local target = GetTarget(self.R.range,"HYB")
		if target and target.health <= target.maxHealth * 0.25 then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.R.range, self.R.delay, self.R.speed, self.R.radius, false)
			if hitChance and hitChance >= BDEurgot.Prediction.RhitChance:Value() then
				self:CastR(target,aimPosition)
			end
		end
	end
end

function Urgot:CastR(target,RcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,RcastPos) <= self.R.range then
			CastSpell(HK_R,RcastPos,self.R.range)
		end
	end
end

function Urgot:Draw()
	if myHero.dead then return end
	if BDEurgot.Drawings.drawQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, self.Q.range, 3, LocalDrawColor(255,000,000,255))
	end
	if BDEurgot.Drawings.drawW:Value() and LocalGameCanUseSpell(_W) == 0 then
		LocalDrawCircle(myHero.pos, self.W.range, 3, LocalDrawColor(255,000,255,000))
	end
	if BDEurgot.Drawings.drawE:Value() and LocalGameCanUseSpell(_E) == 0 then
		LocalDrawCircle(myHero.pos, self.E.range, 3, LocalDrawColor(255,255,255,000))
	end
	if BDEurgot.Drawings.drawR:Value() and LocalGameCanUseSpell(_R) == 0 then
		LocalDrawCircle(myHero.pos, self.R.range, 3, LocalDrawColor(255,255,000,000))
	end
end


class "Xayah"

function Xayah:__init()
	print("Brain.exe - Xayah loaded")
	self.Q = { delay = 0.5,  speed = 2075,  radius = 50, range = 1100 }
	self.Feather = {}
	self.FeatherPos = {}
	self.FeatherCount = 0
	self.FeathersOn = {}
	self.FeathersOnDelay = {}
	self.FeatherHit = 0
	self.FeatherHitDelay = 0
	self.RootAble = 0
	self.RootAbleDelay = 0
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function Xayah:Menu()
	BDExayah = MenuElement({id = "BDExayah", name = "Brain.exe - Xayah", type = MENU})
	
	BDExayah:MenuElement({id = "Script", name = "Script", type = MENU})
	BDExayah:MenuElement({id = "Drawings", name = "Drawings", type = MENU})
	BDExayah:MenuElement({id = "Prediction", name = "Hitchance Manager", type = MENU})

	BDExayah.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDExayah.Script:MenuElement({id = "useW", name = "Use W", value = true})
    BDExayah.Script:MenuElement({id = "useE", name = "Use E", value = true})

	BDExayah.Drawings:MenuElement({id = "drawQ", name = "Draw Q", value = true})
	
	BDExayah.Prediction:MenuElement({id = "QhitChance", name = "Q", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
end

function Xayah:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
	self:OnFeatherUpdate()
	self:FeatherControl()
end

function Xayah:Combo()
	if BDExayah.Script.useE:Value() then
		self:Elogic()
	end
	if BDExayah.Script.useQ:Value() then
		self:Qlogic()
	end
	if BDExayah.Script.useW:Value() then
		self:Wlogic()
	end
end

function Xayah:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.Q.range,"AD")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.Q.range, self.Q.delay, self.Q.speed, self.Q.radius, false)
			if hitChance and hitChance >= BDExayah.Prediction.QhitChance:Value() then
				self:CastQ(target,aimPosition)
			end
		end
	end
end

function Xayah:CastQ(target,QcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,QcastPos) <= self.Q.range then
			LocalControlCastSpell(HK_Q,QcastPos)
		end
	end
end

function Xayah:Wlogic()
	if LocalGameCanUseSpell(_W) == 0 and castSpell.state == 0 then
		local target = GetTarget(myHero.range,"AD")
		if target then
			LocalControlCastSpell(HK_W)
		end
	end
end

function Xayah:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(1300,"AD")
		if target then
			if self.FeathersOn[target.networkID] >= 3 then
				LocalControlCastSpell(HK_E)
			end
		end
	end
end

local countingFeather = 0
function Xayah:OnFeatherUpdate()
	local tick = LocalGetTickCount()
	self.FeatherCount = myHero:GetSpellData(_E).ammo
	if self.FeatherCount ~= #self.Feather then
		if countingFeather + 50 < tick then
			self.Feather = {}
			self.FeatherPos = {}
			_insert(self.FeatherPos,myHero.pos.x..myHero.pos.y..myHero.pos.z)
				for i = 0, LocalGameObjectCount() do
					local o = LocalGameObject(i)
						if o.owner == myHero and o.name == "Feather" and not o.dead and self.Feather[o.pos.x..o.pos.y..o.pos.z] == nil then
							_insert(self.Feather,o)
							self.Feather[o.pos.x..o.pos.y..o.pos.z] = o
						end
				end
			self.FeatherCount = #self.Feather
			countingFeather = tick
		end
	end
end

local counting = 0
function Xayah:FeatherControl()
	local tick = LocalGetTickCount()
	if counting + 100 < tick then
		self.FeathersOn = {}
		self.FeathersOnDelay = {}
		self.RootAble = 0
		self.RootAbleDelay = 0
		self.FeatherHit = 0
		self.FeatherHitDelay = 0
		for i,target in pairs(GetEnemyHeroes()) do
			if not target.dead and target.isTargetable and target.valid and (OnVision(target).state == true or (OnVision(target).state == false and GetTickCount() - OnVision(target).tick < 500)) then
				self.FeathersOn[target.networkID] = 0
				self.FeathersOnDelay[target.networkID] = 0
				self:CountFeatherOn(target)
			end
		end
		counting = tick
	end
end

function Xayah:CountFeatherOn(target)
	if GetDistance(myHero.pos,target.pos) < 5000 then
		for i,f in pairs(self.Feather) do
			local hitChance, targetPos = HPred:GetHitchance(f.pos, target, _huge, 0.1, 4000, 50, false)
			local hitChance2, targetPosDelay = HPred:GetHitchance(f.pos, target, _huge, 0.5, 4000, 50, false)
			local checkPos = myHero.pos + Vector(myHero.pos,f.pos):Normalized()*GetDistance(myHero.pos,target.pos)
			if GetDistance(targetPos,checkPos) < target.boundingRadius + 50 then
				self.FeathersOn[target.networkID] = self.FeathersOn[target.networkID] + .5
				self.FeatherHit = self.FeatherHit + 0.5
			end
			if GetDistance(targetPosDelay,checkPos) < target.boundingRadius + 50 then
				self.FeathersOnDelay[target.networkID] = self.FeathersOnDelay[target.networkID] + .5
				self.FeatherHitDelay = self.FeatherHitDelay + 0.5
			end
		end
		if self.FeathersOnDelay[target.networkID] >= 3 then
			self.RootAbleDelay = self.RootAbleDelay + 1
		end
		if self.FeathersOn[target.networkID] >= 3 then
			self.RootAble = self.RootAble + 1
		end
	end
end

function Xayah:Draw()
	if myHero.dead then return end
	if BDExayah.Drawings.drawQ:Value() and LocalGameCanUseSpell(_Q) == 0 then
		LocalDrawCircle(myHero.pos, self.Q.range, 3, LocalDrawColor(255,000,000,255))
	end
end


class "XinZhao"

function XinZhao:__init()
	print("Brain.exe - XinZhao loaded")
	self.W = { delay = 0.5, speed = _huge, radius = 45, range = 815  }
	self:Menu()
	function OnTick() self:Tick() end
 	function OnDraw() self:Draw() end
end

function XinZhao:Menu()
	BDExinzhao = MenuElement({id = "BDExinzhao", name = "Brain.exe - Xin Zhao", type = MENU})
	
	BDExinzhao:MenuElement({id = "Script", name = "Script", type = MENU})
	BDExinzhao:MenuElement({id = "Drawings", name = "Drawings", type = MENU})
	BDExinzhao:MenuElement({id = "Prediction", name = "Hitchance Manager", type = MENU})

	BDExinzhao.Script:MenuElement({id = "useQ", name = "Use Q", value = true})
	BDExinzhao.Script:MenuElement({id = "useW", name = "Use W", value = true})
	BDExinzhao.Script:MenuElement({id = "useE", name = "Use E", value = true})

	BDExinzhao.Drawings:MenuElement({id = "drawW", name = "Draw W", value = true})
	BDExinzhao.Drawings:MenuElement({id = "drawE", name = "Draw E", value = true})
	BDExinzhao.Drawings:MenuElement({id = "drawR", name = "Draw R", value = true})
	
	BDExinzhao.Prediction:MenuElement({id = "WhitChance", name = "W", value = 2, drop = {"1: Normal","2: Punish Movementation","3: Punish Basic Attacks","4: Punish Spell Casting","5: Undodgeable"}})
end

function XinZhao:Tick()
	if Game.IsChatOpen() then return end
	if myHero.dead then return end
	if GetMode() == "Combo" then
		self:Combo()
	end
	if GetMode() == "Harass" then
		self:Harass()
	end
end

function XinZhao:Combo()
	if BDExinzhao.Script.useW:Value() then
		self:Wlogic()
	end
	if myHero.attackData.state == STATE_WINDDOWN then
		if BDExinzhao.Script.useQ:Value() then
			self:Qlogic()
		end
	end
	if BDExinzhao.Script.useE:Value() then
		self:Elogic()
	end
end

function XinZhao:Harass()
	if BDExinzhao.Script.useW:Value() then
		self:Wlogic()
	end
	if myHero.attackData.state == STATE_WINDDOWN then
		if BDExinzhao.Script.useQ:Value() then
			self:Qlogic()
		end
	end
end

function XinZhao:Qlogic()
	if LocalGameCanUseSpell(_Q) == 0 and castSpell.state == 0 then
		local target = GetTarget(250,"AD")
		if target then
			LocalControlCastSpell(HK_Q)
		end
	end
end

function XinZhao:Wlogic()
	if LocalGameCanUseSpell(_W) == 0 and castSpell.state == 0 then
		local target = GetTarget(self.W.range,"AD")
		if target then
			local hitChance, aimPosition = HPred:GetHitchance(myHero.pos, target, self.W.range, self.W.delay, self.W.speed, self.W.radius, false)
			if hitChance and hitChance >= BDExinzhao.Prediction.WhitChance:Value() then
				self:CastW(target,aimPosition)
			end
		end
	end
end

function XinZhao:CastW(target,WcastPos)
	if LocalGameTimer() - OnWaypoint(target).time > 0.05 and (LocalGameTimer() - OnWaypoint(target).time < 0.125 or LocalGameTimer() - OnWaypoint(target).time > 1.25) then
		if GetDistance(myHero.pos,WcastPos) <= self.W.range then
			LocalControlCastSpell(HK_W,WcastPos)
		end
	end
end

function XinZhao:Elogic()
	if LocalGameCanUseSpell(_E) == 0 and castSpell.state == 0 then
		local target = GetTarget(650,"AD")
		if target then
			LocalControlCastSpell(HK_E,target)
		end
	end
end

function XinZhao:Draw()
	if myHero.dead then return end
	if BDExinzhao.Drawings.drawW:Value() and LocalGameCanUseSpell(_W) == 0 then
		LocalDrawCircle(myHero.pos, self.W.range, 3, LocalDrawColor(255,000,255,000))
	end
	if BDExinzhao.Drawings.drawE:Value() and LocalGameCanUseSpell(_E) == 0 then
		LocalDrawCircle(myHero.pos, 650, 3, LocalDrawColor(255,255,255,000))
	end
	if BDExinzhao.Drawings.drawR:Value() and LocalGameCanUseSpell(_R) == 0 then
		LocalDrawCircle(myHero.pos, 550, 3, LocalDrawColor(255,255,000,000))
	end
end