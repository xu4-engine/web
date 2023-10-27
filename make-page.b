#!/usr/bin/boron -s
; Boron web page creator.
;
; Minimal page file example:
;
;   [html "My Spiffy Projects"]
;   <p>Some text here...</p>
;
; Other bracket keywords (one per line):
;   [ln "https://some.url" "URL link description"]


parts: make block! 200
page-title: none

keywords: context [
    html: func [
        title /style style-href /keywords key-content
        /extern page-title
    ][
        page-title: title
        style-sheet: either style style-href %css/xu4.css
        meta-key: either keywords [
            rejoin [{^/  <meta name="keywords" content="} key-content {" />}]
        ] ""
        construct {{
            <!DOCTYPE html>
            <html lang="en">
            <head>
              <meta charset="utf-8">$MM
              <title>$TT</title>
              <link rel="stylesheet" type="text/css" href="$SS">
            </head>
            <body>
        }}
        [
            "$MM" meta-key
            "$SS" style-sheet
            "$TT" title
        ]
    ]

    ln: func [url desc] [
        construct {<a href="$URL">$DESC</a>} ["$URL" url "$DESC" desc]
    ]

    *ln: func [url desc] [
        construct {<li><a href="$URL">$DESC</a></li>^/}
            ["$URL" url "$DESC" desc]
    ]

    table-row: func [cols /head /local it] [
        str: copy "<tr>"
        blk: pick [
            ["<th>" it "</th>"]
            ["<td>" it "</td>"]
        ] head
        foreach it split cols '|' [
            append str reduce blk
        ]
        append str "</tr>"
    ]

    img_box: func [url alt size] [
        construct {{
          <img src="$TN" alt="$ALT"
            width="$W" height="$H" onclick="img_box('$URL')">
        }}
        reduce [
            "$TN"  thumbnail url
            "$ALT" alt
            "$W"   first size
            "$H"   second size
            "$URL" url
        ]
    ]

    site-menu: func [id /local url title] [
        ; <span class="selectedmenuitem">Home</span> |
        out: construct {{
            <div class="title"><h3><span class="heading">$</span></h3></div>
            <div class="menu">
        }}
        ['$' page-title]

        key: 1
        foreach [url name] [
            "index.html" "Home"
            "screenshots.html" "Screenshots"
           ;"gypsy.html" "Gypsy"
            "https://sourceforge.net/p/xu4/discussion/" "Discussion Forum"
            "faq.html" "FAQ"
            "download.php" "Download"
            "https://sourceforge.net/p/xu4" "Sourceforge"
            "links.html" "Links"
        ][
            span: either eq? id key
            {  <span class="selectedmenuitem">$NAME}
            {  <span class="menuitem"><a accesskey="$KEY" href="$URL">$NAME</a>}

            appair out construct span ["$NAME" name "$KEY" key "$URL" url]
                        {</span> |^/}
            ++ key
        ]
        remove/part skip tail out -3 2
        append  out "</div>^/"
    ]

    sf-footer: does [
        {{
        <div class="footer">
          Hosted at&nbsp; <a href="http://sourceforge.net">
          <img src="http://sourceforge.net/sflogo.php?group_id=36225&amp;type=18" width="210" height="62" alt="SourceForge Logo" style="vertical-align: middle"/>
          </a>
        </div>
        }}
    ]

    news: func [count] [
        out: make string! 1024
        ifn int? count [count: 999]
        parse read/text %page-spec/history.txt [count [
            date: to ' ' :date skip
            line: to '^/' :line (
                append out rejoin [
                    {<li><span class="newsitem"><span class="date">} date
                    {</span> - } line {</span></li>}
                ]
            )
        ]]
        out
    ]
]

; Insert "_tn" before file extension.
thumbnail: func [path] [
    if pos: find/last path '.' [
        return rejoin [slice path pos %_tn pos]
    ]
    path
]

basename: func [path] [
    if pos: find/last path '/' [path: next pos]
    if pos: find/last path '.' [path: slice path pos]
    path
]

white: charset " ^-"
html-page: func [file] [
    clear parts

    spec: read/text file
    parse spec [some[
        any white '[' tok: to ']' :tok skip (append parts to-block tok)
      | any white ';' thru '^/'
      | tok: thru '^/' :tok               (if ne? tok "^/" [append parts tok])
    ]]
    append parts "</body>^/</html>^/"

    ext: switch skip tail file -3 [
        %.bh [%.html]
        %.bp [%.php]
             [%.html]
    ]
    write join basename file ext rejoin bind parts keywords
]

foreach f args [html-page f]
