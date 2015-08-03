function change_star(id, ii)
{
	for(var i=1;i<=id;i++)
	{
		var myElement=document.getElementById(ii.concat(i));
		myElement.className='star';
	}
	
	var i=(id);
	i++;
	while(i<=5)
	{
		var myElement=document.getElementById(ii.concat(i));
		myElement.className='star_gray';
		i++;
	}
}