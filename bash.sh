#!/bin/bash

if [ "$#" -lt 2 ]; then
    # echo "Usage: $0 <file-path> <function-name>"
    exit 1
fi

FILE="$1"
FUNCTION_NAME="$2"

if [[ -f "$FILE" ]]; then
    sed -i "/public function $FUNCTION_NAME()/,/^[[:space:]]*}/d" "$FILE"
    echo "Function $FUNCTION_NAME removed from $FILE"
else
    echo "File not found: $FILE"
    exit 1
fi

