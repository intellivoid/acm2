clean:
	rm -rf build

update:
	ppm --generate-package="src/acm2"

build:
	mkdir build
	ppm --compile="src/acm2" --directory="build"

install:
	ppm --no-prompt --install="build/net.intellivoid.acm2.ppm" --fix-conflict