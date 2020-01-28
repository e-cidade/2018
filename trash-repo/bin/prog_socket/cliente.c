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

int main(int argc, char *argv[]) {
  int sockfd, numbytes;
  char buf[100];
  struct hostent *he;
  struct sockaddr_in outro_end; // informação do endereço externo

  if(argc != 3) {
    fprintf(stderr,"USO: %s ip porta\n");
    exit(-1);
  }
  if((he = gethostbyname(argv[1])) == NULL) {  // pega informação do host
    //fprintf(stderr,"Erro em gethostbyname: %s\n",strerror(errno));
    herror("gethostbyname");
    exit(-1);
  }
  if((sockfd = socket(AF_INET, SOCK_STREAM, 0)) == -1) {
    fprintf(stderr,"Erro em socket: %s\n",strerror(errno));
    exit(-1);
  }
  outro_end.sin_family = AF_INET;    // host byte order
  outro_end.sin_port = htons(atoi(argv[2]));  // converte em short, network byte order
  outro_end.sin_addr = *((struct in_addr *)he->h_addr_list[0]);
  printf("Endereço IP  : %s\n", inet_ntoa(*((struct in_addr *)he->h_addr_list[0] )));
  //outro_end.sin_addr = *((struct in_addr*)he-&gt;h_addr);
  //outro_end.sin_addr.s_addr = inet_addr(argv[1]);
  bzero(&(outro_end.sin_zero), 8);   // zera o resto da estrutura
  if(connect(sockfd, (struct sockaddr *)&outro_end,sizeof(struct sockaddr)) == -1) {
    fprintf(stderr,"Erro em connect: %s\n",strerror(errno));
    exit(-1);
  }
  if((numbytes=recv(sockfd, buf, 100 - 1, 0)) == -1) {
    fprintf(stderr,"Erro em recv: %s\n",strerror(errno));
    exit(-1);
  }
  buf[numbytes] = '\0';
  printf("Recebido: %s",buf);
  strcpy(buf,"string para teste de socket!!!!!!\n");
  if(send(sockfd, buf, strlen(buf),0) == -1) {
    fprintf(stderr,"Erro em send: %s\n",strerror(errno));
    exit(-1);
  }
  close(sockfd);
  return 0;
}