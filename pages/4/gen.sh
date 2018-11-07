#!/bin/bash
#
#

for (( a=1 ; a<=55 ; a++ )) ; do
	echo '<a class=gsc href="imgs/'${a}'.jpeg" target=_blank><img class=iload src="imgs/'${a}'.jpeg.thumb.jpeg" border=0 width=120px align=center /></a>'
done

