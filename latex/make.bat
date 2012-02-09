@echo off
set F=thesis
REM Hier wird der Variablen F der Name der zu kompilierenden LaTeX-Datei zugewiesen, die hier NamederTeXdatei.tex heißt.

:A

echo - pdflatex plods...
pdflatex %F%.tex --max-print-line=800
                       
echo - bibtex plods...
bibtex %F% -quiet 
                  
echo - makeindex plods...
makeindex %F%
echo - pdflatex plods...
pdflatex -quiet %F%.tex --max-print-line=800
echo - pdflatex plods...

pdflatex -quiet %F%.tex --max-print-line=800

echo - Removing *.aux *.bbl *.blg *.ind *.idx *.ilg ...
del *.aux *.dvi *.bbl *.blg *.ind *.idx *.ilg

echo - Done.
echo.