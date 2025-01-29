#!/bin/bash

# Get both arguments
FILE="$1"
FUNCTION="$2"

# Check if file exists
if [[ ! -f "$FILE" ]]; then
    echo "File not found: $FILE"
    exit 1
fi

# More specific deletion targeting the exact function
sed -i "/public function $FUNCTION/,/^    }$/d" "$FILE"

echo "Function $FUNCTION has been removed from $FILE"
