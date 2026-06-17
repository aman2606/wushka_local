==========================================================================
					
							MY STATISTICS README
								  V 1.0


								  
==========================================================================


---------- INDEX ----------
	1. Installation


---------------------------


==========================================================================

 1. Installation
--------------------------------------------------------------------------

Setting up Statistics table for LessonZone resource events.
Stuff to remember:
	- ELEMENTS TO TRACK EVENTS ON:
	--- teacher resources (post_type = post)
	------ Print Resource
	------ Print Free Sample Resource
	------ Download Resource
	------ Download Free Sample Resource
	
	--- eBooks 	  		 (post_type = ebook)
	------ Play eBook
	------ Play Free Sample eBook
	
	--- eBook Support Materials (post_type = post)
	------ Print Resource
	------ Download Resource
	
	- USER ID will be stored along with this information
	
	current table layout:
	- id
	- user_id
	- post_id
	- type (post/ebook/tsm (tsm = ebook support materials)
	- button type ( posts = print/download && ebooks = play && tsm = print/download )
	- sample? - Is the current resource a free sample. (y/n || yes/no || 1/0 || y/null  )
	- event_time - time entry was made
	
==========================================================================
