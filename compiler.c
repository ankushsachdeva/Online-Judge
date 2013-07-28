#include<unistd.h>
#include<stdio.h>
#include<string.h>
int main(int argc,char** argv)
{

	chdir("/var/chroot");
        if (chroot("/var/chroot") != 0) {
        return 127;//failed to open chroot
        }
	if(strcmp(argv[3],"c")!=0){
	    char cmd[]={"g++ -pipe -lm -s -fomit-frame-pointer /"};
	    strcat(cmd,argv[2]);
	    strcat(cmd,".cpp -o ");
	    strcat(cmd,argv[1]);	
	    system(cmd);
	    }
	else{
	    char cmd[]={"gcc -pipe -lm -s -fomit-frame-pointer /"};
	    strcat(cmd,argv[2]);
	    strcat(cmd,".c -o ");
	    strcat(cmd,argv[1]);	
	    system(cmd);
	}
	return 0;
}	
