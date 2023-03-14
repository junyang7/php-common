#!/usr/bin/env bash

set -e

ROOT=$(cd "$(dirname "$0")";pwd)

if [ ! -d vendor ]; then
  composer install
fi

./tool/bin \
--case-dir=${ROOT}/file \
--file-prefix=Test \
--file-suffix=.php \
--namespace=\UnitTest\File \
--method-prefix=test
