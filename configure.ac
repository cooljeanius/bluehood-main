dnl#                                               -*- Autoconf -*-
dnl# Process this file with autoconf to produce a configure script.

AC_PREREQ([2.69])dnl
AC_INIT([bluehood],[0.0.1],[@BlueHood_admin on Twitter])
AC_CONFIG_SRCDIR([include/gettext.h])
AC_CONFIG_HEADERS([config.h])
AC_CONFIG_AUX_DIR([build-aux])
AC_CONFIG_MACRO_DIR([m4])

AC_CANONICAL_TARGET

AM_INIT_AUTOMAKE([foreign])
AM_MAINTAINER_MODE

if test "x${PACKAGE}" = "x" && test "x${PACKAGE_NAME}" != "x"; then
  test -z "${PACKAGE}" && test -n "${PACKAGE_NAME}" && export PACKAGE="${PACKAGE_NAME}"
  AC_DEFINE_UNQUOTED([PACKAGE],["${PACKAGE}"],[Old name for PACKAGE_NAME])
  AC_SUBST([PACKAGE])dnl
  ## (modified from gettext documentation)
fi
if test "x${VERSION}" = "x" && test "x${PACKAGE_VERSION}" != "x"; then
  test -z "${VERSION}" && test -n "${PACKAGE_VERSION}" && export VERSION="${PACKAGE_VERSION}"
  AC_DEFINE_UNQUOTED([VERSION],["${VERSION}"],[Old name for PACKAGE_VERSION])
  AC_SUBST([VERSION])dnl
  ## (modified from gettext documentation)
fi

# Checks for programs.
AC_PROG_CC

# Checks for header files.
AC_CHECK_HEADERS([libintl.h locale.h])

# Checks for typedefs, structures, and compiler characteristics.
AC_C_INLINE
AC_TYPE_SIZE_T

# Checks for library functions.
AC_FUNC_MALLOC
AM_GNU_GETTEXT([external])
AM_GNU_GETTEXT_VERSION([0.19.8])

# Output.
AC_CONFIG_FILES([po/Makefile.in Makefile])
AC_OUTPUT
