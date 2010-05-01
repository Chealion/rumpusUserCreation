//
//  createFTP.m
//  Create FTP User
//
//  Created by Micheal Jones on 04/13/09.
//  Copyright 2009 Micheal Jones. All rights reserved.
//

#import "createFTP.h"
#import <WebKit/WebView.h>
#import <WebKit/WebPolicyDelegate.h>

@implementation createFTP

-(void)awakeFromNib {
	NSString *username = [[NSString alloc] initWithString:@"http://DOMAIN/PATH/TO/ftp.php?secret=secret&username="];
	NSString *createURL = [username stringByAppendingString:NSUserName()];
	
	//NSLog(@"%@", createURL);
	[webView setMainFrameURL:createURL];
	
	[username release];
}

-(BOOL)applicationShouldTerminateAfterLastWindowClosed:(NSApplication*)theApplication
{
	return YES;
}

/* This function is to determine what links should be opened in Safari or which link should be ignored */
- (void)webView:(WebView *)sender decidePolicyForNavigationAction:(NSDictionary *)actionInformation
        request:(NSURLRequest *)request frame:(WebFrame *)frame decisionListener:(id)listener {
    NSString *host = [[request URL] host];
	NSNumber *port = [[request URL] port];

	//Change the port (8080) and the suffix of your AFP URL to suit your setup.
    if ([port isEqualToNumber:[NSNumber numberWithInt:8080]] || [host hasSuffix:@"AFP_DOMAIN_HERE"]) {
		[[NSWorkspace sharedWorkspace] openURL:[request URL]];
		[listener ignore];
    } else {
		[listener use];
	}
}

@end
