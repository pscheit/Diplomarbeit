#!/bin/bash

# Create folder for chapter
mkdir $1

# Change to new folder
cd $1

# Generate main.tex
echo "\input\{$1\}" > main.tex

# Template for content file
echo $1 > $1.tex

# Change back to previous folder
cd -

# Text for thesis.tex
echo "
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
%% $2
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
\part{$1}
\label{part:$1}
\include{$1/main}
"