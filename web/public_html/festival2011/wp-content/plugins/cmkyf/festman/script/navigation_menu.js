var pageindex = 0
var isPreloaded = false


function WM_preloadImages() {

  // Don't bother if there's no document.images
  if (document.images) {
    if (typeof(document.WM) == 'undefined'){
      document.WM = new Object();
    }
    document.WM.loadedImages = new Array();
    // Loop through all the arguments.
    var argLength = WM_preloadImages.arguments.length;
    for(arg=0;arg<argLength;arg++) {
      // For each arg, create a new image.
      document.WM.loadedImages[arg] = new Image();
      // Then set the source of that image to the current argument.
      document.WM.loadedImages[arg].src = WM_preloadImages.arguments[arg];
    }
  }
}

function setupPage()
{
	if (isPreloaded != true)
	{
		WM_preloadImages('images/button_organization_on.gif','images/button_organization_off.gif','images/button_installations_on.gif','images/button_installations_off.gif','images/border_top_left.gif','images/border_top_right.gif','images/bottom.gif','images/button_artists_off.gif','images/button_artists_on.gif','images/button_sponsorsmembers_off.gif','images/button_sponsorsmembers_on.gif','images/button_festivalinfo_off.gif','images/button_festivalinfo_on.gif','images/button_tourism_off.gif','images/button_tourism_on.gif','images/button_films_off.gif','images/button_films_on.gif','images/button_workshops_off.gif','images/button_workshops_on.gif','images/button_performances_off.gif','images/button_performances_on.gif','images/button_program_off.gif','images/button_program_on.gif','images/button_soundclip_off.gif','images/button_soundclip_on.gif','images/button_venuesmaps_off.gif','images/button_venuesmaps_on.gif','images/button_getinvolved_off.gif','images/button_getinvolved_on.gif','images/button_sustainability_off.gif','images/button_sustainability_on.gif','images/button_venuesmaps_off.gif','images/button_venuesmaps_on.gif','images/button_mediaweb_off.gif','images/button_mediaweb_on.gif','images/chrome.gif','images/chrome_performances.gif','images/cmky_logo.gif','images/CMKY_festival_blurb.png','images/CMKY-flower--2inch.gif','images/CMKY-flower--2inch.jpg','images/EVENT.jpg','images/left_of_content.gif','images/mailist_border.gif','images/maillist.gif','images/maillist_border.jpg','images/navbar_border.gif','images/navbar_border.jpg','images/contact_left_spacer.gif','images/sustainability_left_spacer.gif');
		isPreloaded = true;
	}

	populateInitialMenuState(pageindex);
}

function setPageIndex(index)
{
	if (index > 0)
	{
		pageindex = index;
	}
}

function populateInitialMenuState(index)
{
	if (index > 9)
	{
		populatemenu(2);
	}
	else if(index > 4)
	{
		populatemenu(1);
	}
	else
	{
		populatemenu(0);
	}
}

function populatemenu(index)
{
	switch(index) 
	{
		case 0:
		document['button_program'].src = 'images/button_program_on.gif';
		document['button_festival'].src = 'images/button_festivalinfo_off.gif';
		document['button_getinvolved'].src = 'images/button_getinvolved_off.gif';
		document.getElementById('program_sub').className = 'visible';
		document.getElementById('info_sub').className = 'hidden';
		document.getElementById('getinvolved_sub').className = 'hidden';
		break;
		
		case 1:
		document['button_program'].src = 'images/button_program_off.gif';
		document['button_festival'].src = 'images/button_festivalinfo_on.gif';
		document['button_getinvolved'].src = 'images/button_getinvolved_off.gif';
		document.getElementById('program_sub').className = 'hidden';
		document.getElementById('info_sub').className = 'visible';
		document.getElementById('getinvolved_sub').className = 'hidden';
		break;
		
		case 2:
		document['button_program'].src = 'images/button_program_off.gif';
		document['button_festival'].src = 'images/button_festivalinfo_off.gif';
		document['button_getinvolved'].src = 'images/button_getinvolved_on.gif';
		document.getElementById('program_sub').className = 'hidden';
		document.getElementById('info_sub').className = 'hidden';
		document.getElementById('getinvolved_sub').className = 'visible';
		break;
	}
	populateSubMenuState();
}

function populateSubMenuState()
{
	switch(pageindex)
	{
		case 0:
		document['button_performances'].src = 'images/button_performances_on.gif';
		break;
		case 1:
		document['button_artists'].src = 'images/button_artists_on.gif';
		break;
		case 2:
		document['button_films'].src = 'images/button_films_on.gif';
		break;
		case 3:
		document['button_workshops'].src = 'images/button_workshops_on.gif';
		break;
		case 4:
		document['button_installations'].src = 'images/button_installations_on.gif';
		break;
		case 5:
		document['button_tourism'].src = 'images/button_tourism_on.gif';
		break;
		case 6:
		document['button_venuesmaps'].src = 'images/button_venuesmaps_on.gif';
		break;
		case 7:
		document['button_sustainability'].src = 'images/button_sustainability_on.gif';
		break;
		case 8:
		document['button_icas'].src = 'images/button_icas_on.gif';
		break;
		case 9:
		document['button_organization'].src = 'images/button_organization_on.gif';
		break;
		case 10:
		document['button_contact'].src = 'images/button_contact_on.gif';
		break;
		case 11:
		document['button_sponsors'].src = 'images/button_sponsors_on.gif';
		break;
		case 12:
		document['button_members'].src = 'images/button_members_on.gif';
		break;
		case 13:
		document['button_mediaweb'].src = 'images/button_mediaweb_on.gif';
		break;
		
	}
}

function restorePerformancesImageState()
{
	if(pageindex==0) { document['button_performances'].src = 'images/button_performances_on.gif'; }
	else { document['button_performances'].src = 'images/button_performances_off.gif'; }
}

function restoreArtistsImageState()
{
	if(pageindex==1) { document['button_artists'].src = 'images/button_artists_on.gif'; }
	else { document['button_artists'].src = 'images/button_artists_off.gif'; }
}

function restoreFilmsImageState()
{
	if(pageindex==2) { document['button_films'].src = 'images/button_films_on.gif'; }
	else { document['button_films'].src = 'images/button_films_off.gif'; }
}

function restoreWorkshopsImageState()
{
	if(pageindex==3) { document['button_workshops'].src = 'images/button_workshops_on.gif'; }
	else { document['button_workshops'].src = 'images/button_workshops_off.gif'; }
}

function restoreInstallationsImageState()
{
	if(pageindex==4) { document['button_installations'].src = 'images/button_installations_on.gif'; }
	else { document['button_installations'].src = 'images/button_installations_off.gif'; }
}

function restoreTourismImageState()
{
	if(pageindex==5) { document['button_tourism'].src = 'images/button_tourism_on.gif'; }
	else { document['button_tourism'].src = 'images/button_tourism_off.gif'; }
}

function restoreVenuesMapsImageState()
{
	if(pageindex==6) { document['button_venuesmaps'].src = 'images/button_venuesmaps_on.gif'; }
	else { document['button_venuesmaps'].src = 'images/button_venuesmaps_off.gif'; }
}

function restoreSustainabilityImageState()
{
	if(pageindex==7) { document['button_sustainability'].src = 'images/button_sustainability_on.gif'; }
	else { document['button_sustainability'].src = 'images/button_sustainability_off.gif'; }
}

function restoreICASImageState()
{
	if(pageindex==8) { document['button_icas'].src = 'images/button_icas_on.gif'; }
	else { document['button_icas'].src = 'images/button_icas_off.gif'; }
}

function restoreOrganizationImageState()
{
	if(pageindex==9) { document['button_organization'].src = 'images/button_organization_on.gif'; }
	else { document['button_organization'].src = 'images/button_organization_off.gif'; }
}

function restoreContactImageState()
{
	if(pageindex==10) { document['button_contact'].src = 'images/button_contact_on.gif'; }
	else { document['button_contact'].src = 'images/button_contact_off.gif'; }
}

function restoreSponsorsImageState()
{
	if(pageindex==11) { document['button_sponsors'].src = 'images/button_sponsors_on.gif'; }
	else { document['button_sponsors'].src = 'images/button_sponsors_off.gif'; }
}

function restoreMembersImageState()
{
	if(pageindex==11) { document['button_members'].src = 'images/button_members_on.gif'; }
	else { document['button_members'].src = 'images/button_members_off.gif'; }
}

function restoreMediaWebImageState()
{
	if(pageindex==12) { document['button_mediaweb'].src = 'images/button_mediaweb_on.gif'; }
	else { document['button_mediaweb'].src = 'images/button_mediaweb_off.gif'; }
}

