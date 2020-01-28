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

//MODULO: licitação
//CLASSE DA ENTIDADE licitem
class cl_licitem { 
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
   var $l02_tipo = null; 
   var $l02_numero = null; 
   var $l02_item = null; 
   var $l02_descr1 = null; 
   var $l02_descr2 = null; 
   var $l02_descr3 = null; 
   var $l02_descr4 = null; 
   var $l02_descr5 = null; 
   var $l02_descr6 = null; 
   var $l02_unent = null; 
   var $l02_unsai = null; 
   var $l02_quant = 0; 
   var $l02_valor = 0; 
   var $l02_compl01 = null; 
   var $l02_compl02 = null; 
   var $l02_compl03 = null; 
   var $l02_compl04 = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l02_tipo = varchar(1) = Tipo 
                 l02_numero = varchar(8) = Número 
                 l02_item = varchar(7) = Item 
                 l02_descr1 = char(    50) = Descricao do Item 
                 l02_descr2 = char(    50) = Descricao do Item 
                 l02_descr3 = char(    50) = Descricao do Item 
                 l02_descr4 = char(    50) = Descricao do Item 
                 l02_descr5 = char(    50) = Descricao do Item 
                 l02_descr6 = char(    50) = Descricao do Item 
                 l02_unent = char(     7) = Unidade de Entrada 
                 l02_unsai = char(     7) = Unidade de Saida 
                 l02_quant = float8 = Quantidade total do Item 
                 l02_valor = float8 = valor item 
                 l02_compl01 = varchar(45) = Complemento 
                 l02_compl02 = varchar(45) = Complemento 
                 l02_compl03 = varchar(45) = Complemento 
                 l02_compl04 = varchar(45) = Complemento 
                 ";
   //funcao construtor da classe 
   function cl_licitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("licitem"); 
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
       $this->l02_tipo = ($this->l02_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_tipo"]:$this->l02_tipo);
       $this->l02_numero = ($this->l02_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_numero"]:$this->l02_numero);
       $this->l02_item = ($this->l02_item == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_item"]:$this->l02_item);
       $this->l02_descr1 = ($this->l02_descr1 == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_descr1"]:$this->l02_descr1);
       $this->l02_descr2 = ($this->l02_descr2 == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_descr2"]:$this->l02_descr2);
       $this->l02_descr3 = ($this->l02_descr3 == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_descr3"]:$this->l02_descr3);
       $this->l02_descr4 = ($this->l02_descr4 == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_descr4"]:$this->l02_descr4);
       $this->l02_descr5 = ($this->l02_descr5 == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_descr5"]:$this->l02_descr5);
       $this->l02_descr6 = ($this->l02_descr6 == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_descr6"]:$this->l02_descr6);
       $this->l02_unent = ($this->l02_unent == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_unent"]:$this->l02_unent);
       $this->l02_unsai = ($this->l02_unsai == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_unsai"]:$this->l02_unsai);
       $this->l02_quant = ($this->l02_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_quant"]:$this->l02_quant);
       $this->l02_valor = ($this->l02_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_valor"]:$this->l02_valor);
       $this->l02_compl01 = ($this->l02_compl01 == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_compl01"]:$this->l02_compl01);
       $this->l02_compl02 = ($this->l02_compl02 == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_compl02"]:$this->l02_compl02);
       $this->l02_compl03 = ($this->l02_compl03 == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_compl03"]:$this->l02_compl03);
       $this->l02_compl04 = ($this->l02_compl04 == ""?@$GLOBALS["HTTP_POST_VARS"]["l02_compl04"]:$this->l02_compl04);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->l02_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "l02_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l02_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "l02_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l02_item == null ){ 
       $this->erro_sql = " Campo Item nao Informado.";
       $this->erro_campo = "l02_item";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l02_descr1 == null ){ 
       $this->erro_sql = " Campo Descricao do Item nao Informado.";
       $this->erro_campo = "l02_descr1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l02_descr2 == null ){ 
       $this->erro_sql = " Campo Descricao do Item nao Informado.";
       $this->erro_campo = "l02_descr2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l02_descr3 == null ){ 
       $this->erro_sql = " Campo Descricao do Item nao Informado.";
       $this->erro_campo = "l02_descr3";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l02_descr4 == null ){ 
       $this->erro_sql = " Campo Descricao do Item nao Informado.";
       $this->erro_campo = "l02_descr4";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l02_descr5 == null ){ 
       $this->erro_sql = " Campo Descricao do Item nao Informado.";
       $this->erro_campo = "l02_descr5";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l02_descr6 == null ){ 
       $this->erro_sql = " Campo Descricao do Item nao Informado.";
       $this->erro_campo = "l02_descr6";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l02_unent == null ){ 
       $this->erro_sql = " Campo Unidade de Entrada nao Informado.";
       $this->erro_campo = "l02_unent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l02_unsai == null ){ 
       $this->erro_sql = " Campo Unidade de Saida nao Informado.";
       $this->erro_campo = "l02_unsai";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l02_quant == null ){ 
       $this->erro_sql = " Campo Quantidade total do Item nao Informado.";
       $this->erro_campo = "l02_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l02_valor == null ){ 
       $this->erro_sql = " Campo valor item nao Informado.";
       $this->erro_campo = "l02_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into licitem(
                                       l02_tipo 
                                      ,l02_numero 
                                      ,l02_item 
                                      ,l02_descr1 
                                      ,l02_descr2 
                                      ,l02_descr3 
                                      ,l02_descr4 
                                      ,l02_descr5 
                                      ,l02_descr6 
                                      ,l02_unent 
                                      ,l02_unsai 
                                      ,l02_quant 
                                      ,l02_valor 
                                      ,l02_compl01 
                                      ,l02_compl02 
                                      ,l02_compl03 
                                      ,l02_compl04 
                       )
                values (
                                '$this->l02_tipo' 
                               ,'$this->l02_numero' 
                               ,'$this->l02_item' 
                               ,'$this->l02_descr1' 
                               ,'$this->l02_descr2' 
                               ,'$this->l02_descr3' 
                               ,'$this->l02_descr4' 
                               ,'$this->l02_descr5' 
                               ,'$this->l02_descr6' 
                               ,'$this->l02_unent' 
                               ,'$this->l02_unsai' 
                               ,$this->l02_quant 
                               ,$this->l02_valor 
                               ,'$this->l02_compl01' 
                               ,'$this->l02_compl02' 
                               ,'$this->l02_compl03' 
                               ,'$this->l02_compl04' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens da licitacao                                 () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens da licitacao                                 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens da licitacao                                 () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update licitem set ";
     $virgula = "";
     if(trim($this->l02_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_tipo"])){ 
       $sql  .= $virgula." l02_tipo = '$this->l02_tipo' ";
       $virgula = ",";
       if(trim($this->l02_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "l02_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_numero"])){ 
       $sql  .= $virgula." l02_numero = '$this->l02_numero' ";
       $virgula = ",";
       if(trim($this->l02_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "l02_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_item"])){ 
       $sql  .= $virgula." l02_item = '$this->l02_item' ";
       $virgula = ",";
       if(trim($this->l02_item) == null ){ 
         $this->erro_sql = " Campo Item nao Informado.";
         $this->erro_campo = "l02_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_descr1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_descr1"])){ 
       $sql  .= $virgula." l02_descr1 = '$this->l02_descr1' ";
       $virgula = ",";
       if(trim($this->l02_descr1) == null ){ 
         $this->erro_sql = " Campo Descricao do Item nao Informado.";
         $this->erro_campo = "l02_descr1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_descr2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_descr2"])){ 
       $sql  .= $virgula." l02_descr2 = '$this->l02_descr2' ";
       $virgula = ",";
       if(trim($this->l02_descr2) == null ){ 
         $this->erro_sql = " Campo Descricao do Item nao Informado.";
         $this->erro_campo = "l02_descr2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_descr3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_descr3"])){ 
       $sql  .= $virgula." l02_descr3 = '$this->l02_descr3' ";
       $virgula = ",";
       if(trim($this->l02_descr3) == null ){ 
         $this->erro_sql = " Campo Descricao do Item nao Informado.";
         $this->erro_campo = "l02_descr3";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_descr4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_descr4"])){ 
       $sql  .= $virgula." l02_descr4 = '$this->l02_descr4' ";
       $virgula = ",";
       if(trim($this->l02_descr4) == null ){ 
         $this->erro_sql = " Campo Descricao do Item nao Informado.";
         $this->erro_campo = "l02_descr4";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_descr5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_descr5"])){ 
       $sql  .= $virgula." l02_descr5 = '$this->l02_descr5' ";
       $virgula = ",";
       if(trim($this->l02_descr5) == null ){ 
         $this->erro_sql = " Campo Descricao do Item nao Informado.";
         $this->erro_campo = "l02_descr5";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_descr6)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_descr6"])){ 
       $sql  .= $virgula." l02_descr6 = '$this->l02_descr6' ";
       $virgula = ",";
       if(trim($this->l02_descr6) == null ){ 
         $this->erro_sql = " Campo Descricao do Item nao Informado.";
         $this->erro_campo = "l02_descr6";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_unent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_unent"])){ 
       $sql  .= $virgula." l02_unent = '$this->l02_unent' ";
       $virgula = ",";
       if(trim($this->l02_unent) == null ){ 
         $this->erro_sql = " Campo Unidade de Entrada nao Informado.";
         $this->erro_campo = "l02_unent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_unsai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_unsai"])){ 
       $sql  .= $virgula." l02_unsai = '$this->l02_unsai' ";
       $virgula = ",";
       if(trim($this->l02_unsai) == null ){ 
         $this->erro_sql = " Campo Unidade de Saida nao Informado.";
         $this->erro_campo = "l02_unsai";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_quant"])){ 
       $sql  .= $virgula." l02_quant = $this->l02_quant ";
       $virgula = ",";
       if(trim($this->l02_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade total do Item nao Informado.";
         $this->erro_campo = "l02_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_valor"])){ 
       $sql  .= $virgula." l02_valor = $this->l02_valor ";
       $virgula = ",";
       if(trim($this->l02_valor) == null ){ 
         $this->erro_sql = " Campo valor item nao Informado.";
         $this->erro_campo = "l02_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l02_compl01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_compl01"])){ 
       $sql  .= $virgula." l02_compl01 = '$this->l02_compl01' ";
       $virgula = ",";
     }
     if(trim($this->l02_compl02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_compl02"])){ 
       $sql  .= $virgula." l02_compl02 = '$this->l02_compl02' ";
       $virgula = ",";
     }
     if(trim($this->l02_compl03)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_compl03"])){ 
       $sql  .= $virgula." l02_compl03 = '$this->l02_compl03' ";
       $virgula = ",";
     }
     if(trim($this->l02_compl04)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l02_compl04"])){ 
       $sql  .= $virgula." l02_compl04 = '$this->l02_compl04' ";
       $virgula = ",";
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da licitacao                                 nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da licitacao                                 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from licitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da licitacao                                 nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da licitacao                                 nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:licitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>