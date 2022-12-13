<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */
?>
#include<stdio.h>
#include<string.h>
#include<errno.h>
#include<stdlib.h>
#include<unistd.h>
#include<sys/types.h>
#include<sys/socket.h>
#include<sys/wait.h>
#include<netdb.h>
#include<netinet/in.h>

typedef struct {
  char *saida;
  int  fd;
} Sfd;

char *substr(char *str,const char ch) {
  while(*str) {
    if(*str == ch)
      break;
    str++;
  }
  str++;
  return str;
}

void *fazAmao(void *arg) {
  FILE *fp;
  char buf[256];
  char buf2[256];
  int aux;
  Sfd *est;

  est = (Sfd *)arg;
  if((aux = recv(est->fd, buf,255,0)) == -1) {
    perror("send");
    //exit(-1);
  }
  buf[aux] = '\0';

  if((fp = fopen(est->saida,"w")) == NULL) {
    sprintf(buf2,"Erro abrindo dispositivo %s: %s #%i\n",est->saida,strerror(errno),errno);
    send(est->fd, buf2, strlen(buf2),0);
    exit(-1);
  } else
    fprintf(fp,"%s",buf);
  fclose(fp);
  close(est->fd);
}

int main(void) {
  int sockfd, nova_fd;
  struct sockaddr_in meu_end;
  struct sockaddr_in outro_end;
  int sin_size;
  int yes = 1;
  FILE *fp;
  char porta[256];
  char saida[256];
  pthread_t thread_id;
  Sfd *estFD;
  
  estFD = (Sfd *)malloc(sizeof(Sfd));
  if((fp = fopen("/etc/servidor.conf","r")) == NULL) {
    fprintf(stderr,"Erro abrindo arquivo: %s\n",strerror(errno));
    exit(-1);
  }
  fgets(porta,255,fp);
  fgets(saida,255,fp);
  porta[strlen(porta) - 1] = '\0';
  saida[strlen(saida) - 1] = '\0';
  strcpy(porta,substr(porta,'='));
  strcpy(saida,substr(saida,'='));
  fclose(fp);
  
  if((fp = fopen(saida,"r")) == NULL) {
    fprintf(stderr,"Device nao existe: %s %s\n",saida,strerror(errno));
    exit(-1);
  }
  fclose(fp); 
  if((sockfd = socket(AF_INET, SOCK_STREAM, 0)) == -1) {
    fprintf(stderr,"Erro em socket: %s\n",strerror(errno));
    exit(-1);
  }
  if(setsockopt(sockfd,SOL_SOCKET,SO_REUSEADDR,&yes,sizeof(int)) == -1) {
    fprintf(stderr,"Erro em setsockopt: %s\n",strerror(errno));
    exit(-1);
  }
  meu_end.sin_family = AF_INET;
  meu_end.sin_port = htons(atoi(porta));
  meu_end.sin_addr.s_addr = INADDR_ANY;
  bzero(&(meu_end.sin_zero), 8);
  if(bind(sockfd, (struct sockaddr *)&meu_end,sizeof(struct sockaddr))  == -1) {
    fprintf(stderr,"Erro em bind: %s\n",strerror(errno));
    exit(-1);
  }
  if(listen(sockfd, 10 ) == -1) {
    fprintf(stderr,"Erro em listen: %s\n",strerror(errno));
    exit(-1);
  }
  daemon(0,0);
  while(1) {  // loop principal accept()
    sin_size = sizeof(struct sockaddr_in);
    if((nova_fd = accept(sockfd, (struct sockaddr*)&outro_end, &sin_size)) == -1) {
      fprintf(stderr,"Erro em accept: %s\n",strerror(errno));
      continue;
    }
    printf("servidor: recebeu conexão de %s\n",inet_ntoa(outro_end.sin_addr));
    /*
    if(send(nova_fd, "Olá , Beleza!\n", 14,0) == -1) {
      perror("send");
      exit(-1);
    }
    */
    estFD->saida = saida;
    estFD->fd = nova_fd;
    fazAmao((void *)estFD);    
  }
  close(sockfd);
  free(estFD);
  return 0;
}