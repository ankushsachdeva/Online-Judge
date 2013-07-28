#include<sys/stat.h>
#include<sys/types.h>
#include<signal.h>
#include<unistd.h>
#include<stdio.h>
#include<stdlib.h>
#include<fcntl.h>
#include <time.h>
#include <sys/wait.h>
#include<sys/resource.h>
int main(int argc,char ** argv)
{

	/*
	   freopen(argv[3],"r",stdin);
	   freopen("/var/www/newonj/myout","w",stdout);
	 */

	struct timeval start, end;
	long mtime, seconds, useconds; 


	gettimeofday(&start, NULL);


	int input,output,error,flag;


	int oldstdout=dup(1);	


	int pid=fork();
	if(pid==0)
	{

		char arg[12];
		snprintf(arg,11,"%d",getpid());
		int killpid=fork();
		if(killpid==0)
			execl("killer",arg,argv[4],(char *)0);
		else{

			struct rlimit lim,forklim;        
			lim.rlim_cur=atoi(argv[5])*1024*1024;
			lim.rlim_max=lim.rlim_cur;
			chdir("/var/chroot");
			if (chroot("/var/chroot") != 0) {
				printf("chroot /var/chroot");
				return 1;
			}
			output=open(argv[6],O_CREAT|O_TRUNC|O_RDWR,S_IXUSR|S_IRUSR|S_IWUSR|S_IXOTH|S_IROTH|S_IWOTH);
			input=open(argv[2],O_RDWR);

			dup2(input,0);
			dup2(output,1);

			close(input);close(output);

			if(!strcmp(argv[3],"py")){
				lim.rlim_cur+=29963000;//accomodating space for libraries 
				lim.rlim_max=lim.rlim_cur;
				setrlimit(RLIMIT_AS,&lim);
				forklim.rlim_cur=0;
				forklim.rlim_max=0;
				setrlimit(RLIMIT_NPROC,&forklim);
				execl("/usr/bin/python2.6","dummy",argv[1],(char *)0);
			}
			else{
				lim.rlim_cur+=4329900;//accomodating space for libraries 
				lim.rlim_max=lim.rlim_cur;
				setrlimit(RLIMIT_AS,&lim);
				forklim.rlim_cur=0;
				forklim.rlim_max=0;
				setrlimit(RLIMIT_NPROC,&forklim);
				execl(argv[1],(char *)0);
			}
		}
	}
	else
	{




		struct rusage usage;
		int status;
		pid_t w = waitpid(pid, &status, WUNTRACED | WCONTINUED );

		gettimeofday(&end, NULL);

		seconds  = end.tv_sec  - start.tv_sec;
		useconds = end.tv_usec - start.tv_usec;
		mtime = ((seconds) * 1000 + useconds/1000.0) + 0.5;
		dup2(oldstdout,1);
		printf("%ld\n", mtime);



		if (w == -1) {
			perror("waitpid");
			exit(EXIT_FAILURE);
		}
		//if(WIFEXITED(status))printf("normal termination %d\n",WIFEXITED(status));
		if (WIFEXITED(status)) {
			printf("exited, status=%d\n", WEXITSTATUS(status));
			getrusage(RUSAGE_CHILDREN,&usage); 
			printf("%ld\n",usage.ru_maxrss); 

			return 0;
		} 
		else if (WIFSIGNALED(status)) {
			printf("killed by signal %d\n", WTERMSIG(status));
			return  WTERMSIG(status);
		}


	}

	return 0;
}

