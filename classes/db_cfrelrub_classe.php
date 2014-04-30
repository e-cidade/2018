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

//MODULO: pessoal
//CLASSE DA ENTIDADE cfrelrub
class cl_cfrelrub { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $r43_codigo = 0; 
   var $r43_tit1 = null; 
   var $r43_tit2 = null; 
   var $r43_rub1 = null; 
   var $r43_rub2 = null; 
   var $r43_rub3 = null; 
   var $r43_rub4 = null; 
   var $r43_rub5 = null; 
   var $r43_qv1 = null; 
   var $r43_qv2 = null; 
   var $r43_qv3 = null; 
   var $r43_qv4 = null; 
   var $r43_qv5 = null; 
   var $r43_soma = null; 
   var $r43_selec = 0; 
   var $r43_formul = null; 
   var $r43_rub6 = null; 
   var $r43_rub7 = null; 
   var $r43_rub8 = null; 
   var $r43_rub9 = null; 
   var $r43_rub10 = null; 
   var $r43_qv6 = null; 
   var $r43_qv7 = null; 
   var $r43_qv8 = null; 
   var $r43_qv9 = null; 
   var $r43_qv10 = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r43_codigo = int4 = Codigo do relatorio 
                 r43_tit1 = varchar(40) = Título 1 
                 r43_tit2 = varchar(40) = Título 2 
                 r43_rub1 = varchar(4) = Rubrica 1 
                 r43_rub2 = varchar(4) = Rubrica 2 
                 r43_rub3 = varchar(4) = Rubrica 3 
                 r43_rub4 = varchar(4) = Rubrica 4 
                 r43_rub5 = varchar(4) = Rubrica 5 
                 r43_qv1 = varchar(1) = Quantidade ou Valor 1 
                 r43_qv2 = varchar(1) = Quantidade ou Valor 2 
                 r43_qv3 = varchar(1) = Quantidade ou Valor 3 
                 r43_qv4 = varchar(1) = Quantidade ou Valor 4 
                 r43_qv5 = varchar(1) = Quantidade ou Valor 5 
                 r43_soma = varchar(1) = Valor 
                 r43_selec = int4 = selecao 
                 r43_formul = varchar(55) = Fórmula 
                 r43_rub6 = varchar(4) = Rubrica 6 
                 r43_rub7 = varchar(4) = Rubrica 7 
                 r43_rub8 = varchar(4) = Rubrica 8 
                 r43_rub9 = varchar(4) = Rubrica 9 
                 r43_rub10 = varchar(4) = Rubrica 10 
                 r43_qv6 = varchar(1) = Quant./Valor 
                 r43_qv7 = varchar(1) = Quant./Valor 
                 r43_qv8 = varchar(1) = Quant./Valor 
                 r43_qv9 = varchar(1) = Quant./Valor 
                 r43_qv10 = varchar(1) = Quant./Valor 
                 ";
   //funcao construtor da classe 
   function cl_cfrelrub() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cfrelrub"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->r43_codigo = ($this->r43_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_codigo"]:$this->r43_codigo);
       $this->r43_tit1 = ($this->r43_tit1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_tit1"]:$this->r43_tit1);
       $this->r43_tit2 = ($this->r43_tit2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_tit2"]:$this->r43_tit2);
       $this->r43_rub1 = ($this->r43_rub1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_rub1"]:$this->r43_rub1);
       $this->r43_rub2 = ($this->r43_rub2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_rub2"]:$this->r43_rub2);
       $this->r43_rub3 = ($this->r43_rub3 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_rub3"]:$this->r43_rub3);
       $this->r43_rub4 = ($this->r43_rub4 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_rub4"]:$this->r43_rub4);
       $this->r43_rub5 = ($this->r43_rub5 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_rub5"]:$this->r43_rub5);
       $this->r43_qv1 = ($this->r43_qv1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_qv1"]:$this->r43_qv1);
       $this->r43_qv2 = ($this->r43_qv2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_qv2"]:$this->r43_qv2);
       $this->r43_qv3 = ($this->r43_qv3 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_qv3"]:$this->r43_qv3);
       $this->r43_qv4 = ($this->r43_qv4 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_qv4"]:$this->r43_qv4);
       $this->r43_qv5 = ($this->r43_qv5 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_qv5"]:$this->r43_qv5);
       $this->r43_soma = ($this->r43_soma == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_soma"]:$this->r43_soma);
       $this->r43_selec = ($this->r43_selec == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_selec"]:$this->r43_selec);
       $this->r43_formul = ($this->r43_formul == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_formul"]:$this->r43_formul);
       $this->r43_rub6 = ($this->r43_rub6 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_rub6"]:$this->r43_rub6);
       $this->r43_rub7 = ($this->r43_rub7 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_rub7"]:$this->r43_rub7);
       $this->r43_rub8 = ($this->r43_rub8 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_rub8"]:$this->r43_rub8);
       $this->r43_rub9 = ($this->r43_rub9 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_rub9"]:$this->r43_rub9);
       $this->r43_rub10 = ($this->r43_rub10 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_rub10"]:$this->r43_rub10);
       $this->r43_qv6 = ($this->r43_qv6 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_qv6"]:$this->r43_qv6);
       $this->r43_qv7 = ($this->r43_qv7 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_qv7"]:$this->r43_qv7);
       $this->r43_qv8 = ($this->r43_qv8 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_qv8"]:$this->r43_qv8);
       $this->r43_qv9 = ($this->r43_qv9 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_qv9"]:$this->r43_qv9);
       $this->r43_qv10 = ($this->r43_qv10 == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_qv10"]:$this->r43_qv10);
     }else{
       $this->r43_codigo = ($this->r43_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r43_codigo"]:$this->r43_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($r43_codigo){ 
      $this->atualizacampos();
     if($this->r43_tit1 == null ){ 
       $this->erro_sql = " Campo Título 1 nao Informado.";
       $this->erro_campo = "r43_tit1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_tit2 == null ){ 
       $this->erro_sql = " Campo Título 2 nao Informado.";
       $this->erro_campo = "r43_tit2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_rub1 == null ){ 
       $this->erro_sql = " Campo Rubrica 1 nao Informado.";
       $this->erro_campo = "r43_rub1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_rub2 == null ){ 
       $this->erro_sql = " Campo Rubrica 2 nao Informado.";
       $this->erro_campo = "r43_rub2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_rub3 == null ){ 
       $this->erro_sql = " Campo Rubrica 3 nao Informado.";
       $this->erro_campo = "r43_rub3";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_rub4 == null ){ 
       $this->erro_sql = " Campo Rubrica 4 nao Informado.";
       $this->erro_campo = "r43_rub4";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_rub5 == null ){ 
       $this->erro_sql = " Campo Rubrica 5 nao Informado.";
       $this->erro_campo = "r43_rub5";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_qv1 == null ){ 
       $this->erro_sql = " Campo Quantidade ou Valor 1 nao Informado.";
       $this->erro_campo = "r43_qv1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_qv2 == null ){ 
       $this->erro_sql = " Campo Quantidade ou Valor 2 nao Informado.";
       $this->erro_campo = "r43_qv2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_qv3 == null ){ 
       $this->erro_sql = " Campo Quantidade ou Valor 3 nao Informado.";
       $this->erro_campo = "r43_qv3";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_qv4 == null ){ 
       $this->erro_sql = " Campo Quantidade ou Valor 4 nao Informado.";
       $this->erro_campo = "r43_qv4";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_qv5 == null ){ 
       $this->erro_sql = " Campo Quantidade ou Valor 5 nao Informado.";
       $this->erro_campo = "r43_qv5";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_soma == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "r43_soma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_selec == null ){ 
       $this->erro_sql = " Campo selecao nao Informado.";
       $this->erro_campo = "r43_selec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_formul == null ){ 
       $this->erro_sql = " Campo Fórmula nao Informado.";
       $this->erro_campo = "r43_formul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_rub6 == null ){ 
       $this->erro_sql = " Campo Rubrica 6 nao Informado.";
       $this->erro_campo = "r43_rub6";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_rub7 == null ){ 
       $this->erro_sql = " Campo Rubrica 7 nao Informado.";
       $this->erro_campo = "r43_rub7";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_rub8 == null ){ 
       $this->erro_sql = " Campo Rubrica 8 nao Informado.";
       $this->erro_campo = "r43_rub8";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_rub9 == null ){ 
       $this->erro_sql = " Campo Rubrica 9 nao Informado.";
       $this->erro_campo = "r43_rub9";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_rub10 == null ){ 
       $this->erro_sql = " Campo Rubrica 10 nao Informado.";
       $this->erro_campo = "r43_rub10";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_qv6 == null ){ 
       $this->erro_sql = " Campo Quant./Valor nao Informado.";
       $this->erro_campo = "r43_qv6";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_qv7 == null ){ 
       $this->erro_sql = " Campo Quant./Valor nao Informado.";
       $this->erro_campo = "r43_qv7";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_qv8 == null ){ 
       $this->erro_sql = " Campo Quant./Valor nao Informado.";
       $this->erro_campo = "r43_qv8";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_qv9 == null ){ 
       $this->erro_sql = " Campo Quant./Valor nao Informado.";
       $this->erro_campo = "r43_qv9";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r43_qv10 == null ){ 
       $this->erro_sql = " Campo Quant./Valor nao Informado.";
       $this->erro_campo = "r43_qv10";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r43_codigo = $r43_codigo; 
     if(($this->r43_codigo == null) || ($this->r43_codigo == "") ){ 
       $this->erro_sql = " Campo r43_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cfrelrub(
                                       r43_codigo 
                                      ,r43_tit1 
                                      ,r43_tit2 
                                      ,r43_rub1 
                                      ,r43_rub2 
                                      ,r43_rub3 
                                      ,r43_rub4 
                                      ,r43_rub5 
                                      ,r43_qv1 
                                      ,r43_qv2 
                                      ,r43_qv3 
                                      ,r43_qv4 
                                      ,r43_qv5 
                                      ,r43_soma 
                                      ,r43_selec 
                                      ,r43_formul 
                                      ,r43_rub6 
                                      ,r43_rub7 
                                      ,r43_rub8 
                                      ,r43_rub9 
                                      ,r43_rub10 
                                      ,r43_qv6 
                                      ,r43_qv7 
                                      ,r43_qv8 
                                      ,r43_qv9 
                                      ,r43_qv10 
                       )
                values (
                                $this->r43_codigo 
                               ,'$this->r43_tit1' 
                               ,'$this->r43_tit2' 
                               ,'$this->r43_rub1' 
                               ,'$this->r43_rub2' 
                               ,'$this->r43_rub3' 
                               ,'$this->r43_rub4' 
                               ,'$this->r43_rub5' 
                               ,'$this->r43_qv1' 
                               ,'$this->r43_qv2' 
                               ,'$this->r43_qv3' 
                               ,'$this->r43_qv4' 
                               ,'$this->r43_qv5' 
                               ,'$this->r43_soma' 
                               ,$this->r43_selec 
                               ,'$this->r43_formul' 
                               ,'$this->r43_rub6' 
                               ,'$this->r43_rub7' 
                               ,'$this->r43_rub8' 
                               ,'$this->r43_rub9' 
                               ,'$this->r43_rub10' 
                               ,'$this->r43_qv6' 
                               ,'$this->r43_qv7' 
                               ,'$this->r43_qv8' 
                               ,'$this->r43_qv9' 
                               ,'$this->r43_qv10' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de rubricas para relatorio configuravel.  ($this->r43_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de rubricas para relatorio configuravel.  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de rubricas para relatorio configuravel.  ($this->r43_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r43_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r43_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3808,'$this->r43_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,537,3808,'','".AddSlashes(pg_result($resaco,0,'r43_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3809,'','".AddSlashes(pg_result($resaco,0,'r43_tit1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3810,'','".AddSlashes(pg_result($resaco,0,'r43_tit2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3811,'','".AddSlashes(pg_result($resaco,0,'r43_rub1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3812,'','".AddSlashes(pg_result($resaco,0,'r43_rub2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3813,'','".AddSlashes(pg_result($resaco,0,'r43_rub3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3814,'','".AddSlashes(pg_result($resaco,0,'r43_rub4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3815,'','".AddSlashes(pg_result($resaco,0,'r43_rub5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3816,'','".AddSlashes(pg_result($resaco,0,'r43_qv1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3817,'','".AddSlashes(pg_result($resaco,0,'r43_qv2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3818,'','".AddSlashes(pg_result($resaco,0,'r43_qv3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3819,'','".AddSlashes(pg_result($resaco,0,'r43_qv4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3820,'','".AddSlashes(pg_result($resaco,0,'r43_qv5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3821,'','".AddSlashes(pg_result($resaco,0,'r43_soma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3822,'','".AddSlashes(pg_result($resaco,0,'r43_selec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,3823,'','".AddSlashes(pg_result($resaco,0,'r43_formul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,4584,'','".AddSlashes(pg_result($resaco,0,'r43_rub6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,4585,'','".AddSlashes(pg_result($resaco,0,'r43_rub7'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,4586,'','".AddSlashes(pg_result($resaco,0,'r43_rub8'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,4587,'','".AddSlashes(pg_result($resaco,0,'r43_rub9'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,4588,'','".AddSlashes(pg_result($resaco,0,'r43_rub10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,4589,'','".AddSlashes(pg_result($resaco,0,'r43_qv6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,4590,'','".AddSlashes(pg_result($resaco,0,'r43_qv7'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,4591,'','".AddSlashes(pg_result($resaco,0,'r43_qv8'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,4592,'','".AddSlashes(pg_result($resaco,0,'r43_qv9'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,537,4593,'','".AddSlashes(pg_result($resaco,0,'r43_qv10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r43_codigo=null) { 
      $this->atualizacampos();
     $sql = " update cfrelrub set ";
     $virgula = "";
     if(trim($this->r43_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_codigo"])){ 
       $sql  .= $virgula." r43_codigo = $this->r43_codigo ";
       $virgula = ",";
       if(trim($this->r43_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo do relatorio nao Informado.";
         $this->erro_campo = "r43_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_tit1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_tit1"])){ 
       $sql  .= $virgula." r43_tit1 = '$this->r43_tit1' ";
       $virgula = ",";
       if(trim($this->r43_tit1) == null ){ 
         $this->erro_sql = " Campo Título 1 nao Informado.";
         $this->erro_campo = "r43_tit1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_tit2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_tit2"])){ 
       $sql  .= $virgula." r43_tit2 = '$this->r43_tit2' ";
       $virgula = ",";
       if(trim($this->r43_tit2) == null ){ 
         $this->erro_sql = " Campo Título 2 nao Informado.";
         $this->erro_campo = "r43_tit2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_rub1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_rub1"])){ 
       $sql  .= $virgula." r43_rub1 = '$this->r43_rub1' ";
       $virgula = ",";
       if(trim($this->r43_rub1) == null ){ 
         $this->erro_sql = " Campo Rubrica 1 nao Informado.";
         $this->erro_campo = "r43_rub1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_rub2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_rub2"])){ 
       $sql  .= $virgula." r43_rub2 = '$this->r43_rub2' ";
       $virgula = ",";
       if(trim($this->r43_rub2) == null ){ 
         $this->erro_sql = " Campo Rubrica 2 nao Informado.";
         $this->erro_campo = "r43_rub2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_rub3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_rub3"])){ 
       $sql  .= $virgula." r43_rub3 = '$this->r43_rub3' ";
       $virgula = ",";
       if(trim($this->r43_rub3) == null ){ 
         $this->erro_sql = " Campo Rubrica 3 nao Informado.";
         $this->erro_campo = "r43_rub3";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_rub4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_rub4"])){ 
       $sql  .= $virgula." r43_rub4 = '$this->r43_rub4' ";
       $virgula = ",";
       if(trim($this->r43_rub4) == null ){ 
         $this->erro_sql = " Campo Rubrica 4 nao Informado.";
         $this->erro_campo = "r43_rub4";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_rub5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_rub5"])){ 
       $sql  .= $virgula." r43_rub5 = '$this->r43_rub5' ";
       $virgula = ",";
       if(trim($this->r43_rub5) == null ){ 
         $this->erro_sql = " Campo Rubrica 5 nao Informado.";
         $this->erro_campo = "r43_rub5";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_qv1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_qv1"])){ 
       $sql  .= $virgula." r43_qv1 = '$this->r43_qv1' ";
       $virgula = ",";
       if(trim($this->r43_qv1) == null ){ 
         $this->erro_sql = " Campo Quantidade ou Valor 1 nao Informado.";
         $this->erro_campo = "r43_qv1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_qv2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_qv2"])){ 
       $sql  .= $virgula." r43_qv2 = '$this->r43_qv2' ";
       $virgula = ",";
       if(trim($this->r43_qv2) == null ){ 
         $this->erro_sql = " Campo Quantidade ou Valor 2 nao Informado.";
         $this->erro_campo = "r43_qv2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_qv3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_qv3"])){ 
       $sql  .= $virgula." r43_qv3 = '$this->r43_qv3' ";
       $virgula = ",";
       if(trim($this->r43_qv3) == null ){ 
         $this->erro_sql = " Campo Quantidade ou Valor 3 nao Informado.";
         $this->erro_campo = "r43_qv3";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_qv4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_qv4"])){ 
       $sql  .= $virgula." r43_qv4 = '$this->r43_qv4' ";
       $virgula = ",";
       if(trim($this->r43_qv4) == null ){ 
         $this->erro_sql = " Campo Quantidade ou Valor 4 nao Informado.";
         $this->erro_campo = "r43_qv4";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_qv5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_qv5"])){ 
       $sql  .= $virgula." r43_qv5 = '$this->r43_qv5' ";
       $virgula = ",";
       if(trim($this->r43_qv5) == null ){ 
         $this->erro_sql = " Campo Quantidade ou Valor 5 nao Informado.";
         $this->erro_campo = "r43_qv5";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_soma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_soma"])){ 
       $sql  .= $virgula." r43_soma = '$this->r43_soma' ";
       $virgula = ",";
       if(trim($this->r43_soma) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "r43_soma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_selec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_selec"])){ 
       $sql  .= $virgula." r43_selec = $this->r43_selec ";
       $virgula = ",";
       if(trim($this->r43_selec) == null ){ 
         $this->erro_sql = " Campo selecao nao Informado.";
         $this->erro_campo = "r43_selec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_formul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_formul"])){ 
       $sql  .= $virgula." r43_formul = '$this->r43_formul' ";
       $virgula = ",";
       if(trim($this->r43_formul) == null ){ 
         $this->erro_sql = " Campo Fórmula nao Informado.";
         $this->erro_campo = "r43_formul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_rub6)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_rub6"])){ 
       $sql  .= $virgula." r43_rub6 = '$this->r43_rub6' ";
       $virgula = ",";
       if(trim($this->r43_rub6) == null ){ 
         $this->erro_sql = " Campo Rubrica 6 nao Informado.";
         $this->erro_campo = "r43_rub6";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_rub7)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_rub7"])){ 
       $sql  .= $virgula." r43_rub7 = '$this->r43_rub7' ";
       $virgula = ",";
       if(trim($this->r43_rub7) == null ){ 
         $this->erro_sql = " Campo Rubrica 7 nao Informado.";
         $this->erro_campo = "r43_rub7";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_rub8)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_rub8"])){ 
       $sql  .= $virgula." r43_rub8 = '$this->r43_rub8' ";
       $virgula = ",";
       if(trim($this->r43_rub8) == null ){ 
         $this->erro_sql = " Campo Rubrica 8 nao Informado.";
         $this->erro_campo = "r43_rub8";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_rub9)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_rub9"])){ 
       $sql  .= $virgula." r43_rub9 = '$this->r43_rub9' ";
       $virgula = ",";
       if(trim($this->r43_rub9) == null ){ 
         $this->erro_sql = " Campo Rubrica 9 nao Informado.";
         $this->erro_campo = "r43_rub9";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_rub10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_rub10"])){ 
       $sql  .= $virgula." r43_rub10 = '$this->r43_rub10' ";
       $virgula = ",";
       if(trim($this->r43_rub10) == null ){ 
         $this->erro_sql = " Campo Rubrica 10 nao Informado.";
         $this->erro_campo = "r43_rub10";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_qv6)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_qv6"])){ 
       $sql  .= $virgula." r43_qv6 = '$this->r43_qv6' ";
       $virgula = ",";
       if(trim($this->r43_qv6) == null ){ 
         $this->erro_sql = " Campo Quant./Valor nao Informado.";
         $this->erro_campo = "r43_qv6";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_qv7)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_qv7"])){ 
       $sql  .= $virgula." r43_qv7 = '$this->r43_qv7' ";
       $virgula = ",";
       if(trim($this->r43_qv7) == null ){ 
         $this->erro_sql = " Campo Quant./Valor nao Informado.";
         $this->erro_campo = "r43_qv7";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_qv8)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_qv8"])){ 
       $sql  .= $virgula." r43_qv8 = '$this->r43_qv8' ";
       $virgula = ",";
       if(trim($this->r43_qv8) == null ){ 
         $this->erro_sql = " Campo Quant./Valor nao Informado.";
         $this->erro_campo = "r43_qv8";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_qv9)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_qv9"])){ 
       $sql  .= $virgula." r43_qv9 = '$this->r43_qv9' ";
       $virgula = ",";
       if(trim($this->r43_qv9) == null ){ 
         $this->erro_sql = " Campo Quant./Valor nao Informado.";
         $this->erro_campo = "r43_qv9";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r43_qv10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r43_qv10"])){ 
       $sql  .= $virgula." r43_qv10 = '$this->r43_qv10' ";
       $virgula = ",";
       if(trim($this->r43_qv10) == null ){ 
         $this->erro_sql = " Campo Quant./Valor nao Informado.";
         $this->erro_campo = "r43_qv10";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r43_codigo!=null){
       $sql .= " r43_codigo = $this->r43_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r43_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3808,'$this->r43_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_codigo"]))
           $resac = db_query("insert into db_acount values($acount,537,3808,'".AddSlashes(pg_result($resaco,$conresaco,'r43_codigo'))."','$this->r43_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_tit1"]))
           $resac = db_query("insert into db_acount values($acount,537,3809,'".AddSlashes(pg_result($resaco,$conresaco,'r43_tit1'))."','$this->r43_tit1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_tit2"]))
           $resac = db_query("insert into db_acount values($acount,537,3810,'".AddSlashes(pg_result($resaco,$conresaco,'r43_tit2'))."','$this->r43_tit2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_rub1"]))
           $resac = db_query("insert into db_acount values($acount,537,3811,'".AddSlashes(pg_result($resaco,$conresaco,'r43_rub1'))."','$this->r43_rub1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_rub2"]))
           $resac = db_query("insert into db_acount values($acount,537,3812,'".AddSlashes(pg_result($resaco,$conresaco,'r43_rub2'))."','$this->r43_rub2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_rub3"]))
           $resac = db_query("insert into db_acount values($acount,537,3813,'".AddSlashes(pg_result($resaco,$conresaco,'r43_rub3'))."','$this->r43_rub3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_rub4"]))
           $resac = db_query("insert into db_acount values($acount,537,3814,'".AddSlashes(pg_result($resaco,$conresaco,'r43_rub4'))."','$this->r43_rub4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_rub5"]))
           $resac = db_query("insert into db_acount values($acount,537,3815,'".AddSlashes(pg_result($resaco,$conresaco,'r43_rub5'))."','$this->r43_rub5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_qv1"]))
           $resac = db_query("insert into db_acount values($acount,537,3816,'".AddSlashes(pg_result($resaco,$conresaco,'r43_qv1'))."','$this->r43_qv1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_qv2"]))
           $resac = db_query("insert into db_acount values($acount,537,3817,'".AddSlashes(pg_result($resaco,$conresaco,'r43_qv2'))."','$this->r43_qv2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_qv3"]))
           $resac = db_query("insert into db_acount values($acount,537,3818,'".AddSlashes(pg_result($resaco,$conresaco,'r43_qv3'))."','$this->r43_qv3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_qv4"]))
           $resac = db_query("insert into db_acount values($acount,537,3819,'".AddSlashes(pg_result($resaco,$conresaco,'r43_qv4'))."','$this->r43_qv4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_qv5"]))
           $resac = db_query("insert into db_acount values($acount,537,3820,'".AddSlashes(pg_result($resaco,$conresaco,'r43_qv5'))."','$this->r43_qv5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_soma"]))
           $resac = db_query("insert into db_acount values($acount,537,3821,'".AddSlashes(pg_result($resaco,$conresaco,'r43_soma'))."','$this->r43_soma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_selec"]))
           $resac = db_query("insert into db_acount values($acount,537,3822,'".AddSlashes(pg_result($resaco,$conresaco,'r43_selec'))."','$this->r43_selec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_formul"]))
           $resac = db_query("insert into db_acount values($acount,537,3823,'".AddSlashes(pg_result($resaco,$conresaco,'r43_formul'))."','$this->r43_formul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_rub6"]))
           $resac = db_query("insert into db_acount values($acount,537,4584,'".AddSlashes(pg_result($resaco,$conresaco,'r43_rub6'))."','$this->r43_rub6',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_rub7"]))
           $resac = db_query("insert into db_acount values($acount,537,4585,'".AddSlashes(pg_result($resaco,$conresaco,'r43_rub7'))."','$this->r43_rub7',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_rub8"]))
           $resac = db_query("insert into db_acount values($acount,537,4586,'".AddSlashes(pg_result($resaco,$conresaco,'r43_rub8'))."','$this->r43_rub8',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_rub9"]))
           $resac = db_query("insert into db_acount values($acount,537,4587,'".AddSlashes(pg_result($resaco,$conresaco,'r43_rub9'))."','$this->r43_rub9',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_rub10"]))
           $resac = db_query("insert into db_acount values($acount,537,4588,'".AddSlashes(pg_result($resaco,$conresaco,'r43_rub10'))."','$this->r43_rub10',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_qv6"]))
           $resac = db_query("insert into db_acount values($acount,537,4589,'".AddSlashes(pg_result($resaco,$conresaco,'r43_qv6'))."','$this->r43_qv6',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_qv7"]))
           $resac = db_query("insert into db_acount values($acount,537,4590,'".AddSlashes(pg_result($resaco,$conresaco,'r43_qv7'))."','$this->r43_qv7',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_qv8"]))
           $resac = db_query("insert into db_acount values($acount,537,4591,'".AddSlashes(pg_result($resaco,$conresaco,'r43_qv8'))."','$this->r43_qv8',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_qv9"]))
           $resac = db_query("insert into db_acount values($acount,537,4592,'".AddSlashes(pg_result($resaco,$conresaco,'r43_qv9'))."','$this->r43_qv9',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r43_qv10"]))
           $resac = db_query("insert into db_acount values($acount,537,4593,'".AddSlashes(pg_result($resaco,$conresaco,'r43_qv10'))."','$this->r43_qv10',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de rubricas para relatorio configuravel.  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r43_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de rubricas para relatorio configuravel.  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r43_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r43_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r43_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r43_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3808,'$r43_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,537,3808,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3809,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_tit1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3810,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_tit2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3811,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_rub1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3812,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_rub2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3813,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_rub3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3814,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_rub4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3815,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_rub5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3816,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_qv1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3817,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_qv2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3818,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_qv3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3819,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_qv4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3820,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_qv5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3821,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_soma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3822,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_selec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,3823,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_formul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,4584,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_rub6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,4585,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_rub7'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,4586,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_rub8'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,4587,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_rub9'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,4588,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_rub10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,4589,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_qv6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,4590,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_qv7'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,4591,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_qv8'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,4592,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_qv9'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,537,4593,'','".AddSlashes(pg_result($resaco,$iresaco,'r43_qv10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cfrelrub
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r43_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r43_codigo = $r43_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de rubricas para relatorio configuravel.  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r43_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de rubricas para relatorio configuravel.  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r43_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r43_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:cfrelrub";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>