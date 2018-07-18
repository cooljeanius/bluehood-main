PACKAGE = @PACKAGE@
VERSION = @VERSION@

ACLOCAL_AMFLAGS = -I m4 --install

SUBDIRS = po

DISTCLEANFILES = *~ autoscan.log po/*~

distclean-local:
	-rm -rf autom4te.cache || rmdir autom4te.cache
