cat u.txt  | grep youtube | sed 's/^.*v=//' | xargs -I {} bash -c "cat uc.txt | sed 's/{X}/{}/g'" >final_u.txt