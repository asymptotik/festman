
function getFileSuffix(filename)
{
	var ret = null;

	if(filename.length > 0)
	{
		var index = filename.lastIndexOf(".");
		if(index == -1 || filename.length - (index + 1) == 0)
		{
			return null;
		}
		ret = filename.substr(index + 1, filename.length - (index + 1));
	}

	return ret;
}