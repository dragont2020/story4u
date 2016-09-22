<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" 
                xmlns:html="http://www.w3.org/TR/REC-html40"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title>XML Sitemap</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			 <style type="text/css">
                    body {
                        font-family: Helvetica, Arial, sans-serif;
                        font-size: 13px;
                        color: #545353;
                    }
                    table {
                        border: none;
                        border-collapse: collapse;
                    }
                    #sitemap tr.odd td {
                        background-color: #eee !important;
                    }
                    #sitemap tbody tr:hover td {
                        background-color: #ccc;
                    }
                    #sitemap tbody tr:hover td, #sitemap tbody tr:hover td a {
                        color: #000;
                    }
                    #content {
                        margin: 0 auto;
                        width: 1000px;
                    }
                    a {
                        color: #000;
                        text-decoration: none;
                    }
                    a:visited {
                        color: #777;
                    }
                    a:hover {
                        text-decoration: underline;
                    }
                    td {
                        font-size:11px;
                    }
                    th {
                        text-align:left;
                        padding-right:30px;
                        font-size:11px;
                    }
                    thead th {
                        border-bottom: 1px solid #000;
                        cursor: pointer;
                    }
                </style>

			</head>
			<body>
				<h1>MStory.Ga Sitemap</h1>
				<div id="intro">
					<p>
						This is a XML Sitemap which is supposed to be processed by search engines like <a href="http://www.google.com">Google</a>, <a href="http://search.msn.com">MSN Search</a> and <a href="http://www.yahoo.com">YAHOO</a>.<br />
						You can find more information about XML sitemaps on <a href="http://sitemaps.org">sitemaps.org</a> and Google's <a href="http://code.google.com/sm_thirdparty.html">list of sitemap programs</a>.
					</p>
				</div>
				<div id="content">
					<table id="sitemap" cellpadding="3">
                        <thead>
    						<tr>
    							<th>URL</th>
    							<th>Prio</th>
    							<th>Ch. Freq</th>
    							<th>Last Mod</th>
    						</tr>
                        </thead>
                        <tbody>
        					<xsl:variable name="lower" select="'abcdefghijklmnopqrstuvwxyz'"/>
        					<xsl:variable name="upper" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ'"/>
        					<xsl:for-each select="sitemap:urlset/sitemap:url">
        						<tr>
        							<td>
        								<xsl:variable name="itemURL">
        									<xsl:value-of select="sitemap:loc"/>
        								</xsl:variable>
        								<a href="{$itemURL}">
        									<xsl:value-of select="sitemap:loc"/>
        								</a>
        							</td>
        							<td>
        								<xsl:value-of select="concat(sitemap:priority*100,'%')"/>
        							</td>
        							<td>
        								<xsl:value-of select="concat(translate(substring(sitemap:changefreq, 1, 1),concat($lower, $upper),concat($upper, $lower)),substring(sitemap:changefreq, 2))"/>
        							</td>
        							<td>
        								<xsl:value-of select="concat(substring(sitemap:lastmod,0,11),concat(' ', substring(sitemap:lastmod,12,5)))"/>
        							</td>
        						</tr>
        					</xsl:for-each>
                        </tbody>
					</table>
				</div>
				<div id="footer">
					This XSLT template is released under GPL.
				</div>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>