HTML=$(subst .bh,.html,$(notdir $(wildcard page-spec/*.bh)))
PHP =$(subst .bp,.php,$(notdir $(wildcard page-spec/*.bp)))

%.html: page-spec/%.bh
	boron -s make-page.b $<

%.php: page-spec/%.bp
	boron -s make-page.b $<

default: $(HTML) $(PHP)
