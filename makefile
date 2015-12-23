version = 0_0_00
outfile = Joomla_nl2go_$(version).zip
objects = tmp/pkg_newsletter2go.xml \
	tmp/packages/com_newsletter2go.zip \
	tmp/packages/mod_newsletter2go.zip

$(version): $(outfile)

$(outfile): tmp/build.zip
	mv tmp/build.zip $(outfile)

tmp/build.zip: $(objects)
	cd tmp/ && zip -r build.zip *

tmp:
	mkdir -p tmp/packages

tmp/pkg_newsletter2go.xml: tmp
	cp src/pkg_newsletter2go.xml tmp/

tmp/packages/com_newsletter2go.zip: tmp
	cd src/packages/ && zip -r ../../tmp/packages/com_newsletter2go.zip com_newsletter2go/

tmp/packages/mod_newsletter2go.zip: tmp
	cd src/packages/ && zip -r ../../tmp/packages/mod_newsletter2go.zip mod_newsletter2go/

.PHONY: svn
svn:
	cp -r src/* svn/trunk ; \
	cp -r assets/* svn/assets

.PHONY: clean
clean:
	rm -rf tmp
