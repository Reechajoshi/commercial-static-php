for F in $(ls -1 *.jpeg) ; do
	convert $F -resize 120x temp.png
	H=$(file temp.png  | sed 's/^.* PNG image, //' | awk '{print $3}' | tr -d ',')
	if [ ${H} -gt 75 ] ; then 
		C=$(( ${H} - 75 ))
		convert temp.png -gravity South -chop x${C}+0+0 "${F}.thumb.jpeg"
	else
		# convert temp.png thumb.250.jpeg
		convert $F -resize 120x -background transparent -compose Copy -gravity center -extent 120x75 "${F}.thumb.jpeg"
	fi
	
	rm -f temp.png
done
