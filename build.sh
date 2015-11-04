mkdir tmp &&
mkdir tmp/packages &&
zip -r tmp/packages/com_newsletter2go.zip src/packages/com_newsletter2go/ &&
zip -r tmp/packages/mod_newsletter2go.zip src/packages/mod_newsletter2go/ &&
cp src/pkg_newsletter2go.xml tmp/ &&
zip -r pkg_newsletter2go_v3.0.00.zip tmp/ &&
rm -rf tmp/
