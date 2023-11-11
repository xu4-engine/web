SSH_URL=$(SF_USER)@web.sourceforge.net:/home/project-web/xu4/htdocs

HTML=$(subst .bh,.html,$(notdir $(wildcard page-spec/*.bh)))
PHP =$(subst .bp,.php,$(notdir $(wildcard page-spec/*.bp)))

%.html: page-spec/%.bh
	boron -s make-page.b $<

%.php: page-spec/%.bp
	boron -s make-page.b $<

default: $(HTML) $(PHP)

update:
	rsync -av -e ssh *.html *.php css images download $(SSH_URL)

# NOTE: The AmiU4Shots, webmaps, & sheetmusic directories could be included
#       in the update, but they haven't changed in years.

dry:
	rsync -av -n -e ssh *.html *.php css images download $(SSH_URL)

fetch_dl:
	rsync -av -e ssh $(SSH_URL)/download .

update_dl:
	rsync -av -e ssh download $(SSH_URL)
