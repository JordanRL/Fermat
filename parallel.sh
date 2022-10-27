#!/bin/bash
if [ "$CIRCLE_NODE_INDEX" == 0 ]; then
  export PHPUNIT_SUITE=Core
elif [ "$CIRCLE_NODE_INDEX" == 1 ]; then
  export PHPUNIT_SUITE=Complex
elif [ "$CIRCLE_NODE_INDEX" == 2 ]; then
  export PHPUNIT_SUITE=Coordinates
elif [ "$CIRCLE_NODE_INDEX" == 3 ]; then
  export PHPUNIT_SUITE=Expressions
elif [ "$CIRCLE_NODE_INDEX" == 4 ]; then
  export PHPUNIT_SUITE=LinearAlgebra
else
  export PHPUNIT_SUITE=Stats
fi