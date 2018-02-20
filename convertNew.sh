#!/bin/sh
#echo syntaxe : $0 repertoire_cible fichiers
#could optimize by making a list of all files, and at the end check if it's not empty, and call it all at once

getSuffix(){
	echo $1 | rev | cut -d"." -f1 | rev
}

convert(){
	libreoffice --headless --convert-to pdf --outdir $1 $2
}

#test for the flag. 
#if the first arg is a directory, then do the script normally
#otherwise, it means a flag could be present
#and the first argument is the filename of the flag


if test -f $1 #is a file
then
	fsize=$(wc -c $1 | awk '{print $1}')
	if test $fsize -le 10 #size small enough for a simple flag file
	then
		echo found a flag, commencing check + convertion
		rm $1
		shift
	else
		echo that is way too big for a flag file!
		exit
	fi
elif test -d $1 #is a dir
then
	echo normal behaviour
else
	echo no flag found
	exit
fi

#end of the search for a flag, start of the normal check+convert

cible=$1
shift

for f in $*
do
	for i in doc docx pdf rtf xls txt docm xlsx ppt pptx csv
	do
		if test `getSuffix $f` = $i 
		then

			fbase=`basename -s".$i" $f`
			t=$cible/$fbase".pdf"
			if test -e $t
			then	
				if test $f -nt $t #is $f more recent than $t
				then
					echo $f is more recent than $t
					convert $cible/ $f
				else
					echo $f is older than $t
				fi
			else
				convert $cible/ $f
			fi
		fi
	done
done
