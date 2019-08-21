#!/usr/bin/env bash
./bin/generate-reflection.php
protoc -I./proto --phpmeta_out=gen-src proto/google/protobuf/compiler/plugin.proto
./bin/meta.php meta --spec='Skrz\Meta\Protobuf\ProtobufBootstrapMetaSpec' --directory=`pwd`/gen-src/Google/Protobuf

protoc -I./proto --phpmeta_out=gen-src proto/skrz/meta/fixtures/protobuf/interoperability.proto
