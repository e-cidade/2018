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

//MODULO: patrim
//CLASSE DA ENTIDADE clabens_ant
class cl_clabens_ant { 
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
   var $t01_class = null; 
   var $t01_descr = null; 
   var $t01_deprec = 0; 
   var $t01_descr1 = null; 
   var $t01_descr2 = null; 
   var $t01_descr3 = null; 
   var $t01_descr4 = null; 
   var $t01_descr5 = null; 
   var $t01_descr6 = null; 
   var $t01_plano = null; 
   var $t01_ultcod = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t01_class = char(     8) = Codigo de Classificacao do Bem 
                 t01_descr = char(    35) = Descricao da Classificacao 
                 t01_deprec = float8 = Percentual de Depreciacao 
                 t01_descr1 = char(    20) = Descricao 1 para o Bem 
                 t01_descr2 = char(    20) = Descricao 2 
                 t01_descr3 = char(    20) = Descricao 3 
                 t01_descr4 = char(    20) = Descricao 4 
                 t01_descr5 = char(    20) = Descricao 5 
                 t01_descr6 = char(    20) = Descricao 6 
                 t01_plano = char(    10) = Plano de Contas 
                 t01_ultcod = int4 = Numero do Ultimo codigo Cad. 
                 ";
   //funcao construtor da classe 
   function cl_clabens_ant() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("clabens_ant"); 
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
       $this->t01_class = ($this->t01_class == ""?@$GLOBALS["HTTP_POST_VARS"]["t01_class"]:$this->t01_class);
       $this->t01_descr = ($this->t01_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["t01_descr"]:$this->t01_descr);
       $this->t01_deprec = ($this->t01_deprec == ""?@$GLOBALS["HTTP_POST_VARS"]["t01_deprec"]:$this->t01_deprec);
       $this->t01_descr1 = ($this->t01_descr1 == ""?@$GLOBALS["HTTP_POST_VARS"]["t01_descr1"]:$this->t01_descr1);
       $this->t01_descr2 = ($this->t01_descr2 == ""?@$GLOBALS["HTTP_POST_VARS"]["t01_descr2"]:$this->t01_descr2);
       $this->t01_descr3 = ($this->t01_descr3 == ""?@$GLOBALS["HTTP_POST_VARS"]["t01_descr3"]:$this->t01_descr3);
       $this->t01_descr4 = ($this->t01_descr4 == ""?@$GLOBALS["HTTP_POST_VARS"]["t01_descr4"]:$this->t01_descr4);
       $this->t01_descr5 = ($this->t01_descr5 == ""?@$GLOBALS["HTTP_POST_VARS"]["t01_descr5"]:$this->t01_descr5);
       $this->t01_descr6 = ($this->t01_descr6 == ""?@$GLOBALS["HTTP_POST_VARS"]["t01_descr6"]:$this->t01_descr6);
       $this->t01_plano = ($this->t01_plano == ""?@$GLOBALS["HTTP_POST_VARS"]["t01_plano"]:$this->t01_plano);
       $this->t01_ultcod = ($this->t01_ultcod == ""?@$GLOBALS["HTTP_POST_VARS"]["t01_ultcod"]:$this->t01_ultcod);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->t01_class == null ){ 
       $this->erro_sql = " Campo Codigo de Classificacao do Bem nao Informado.";
       $this->erro_campo = "t01_class";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t01_descr == null ){ 
       $this->erro_sql = " Campo Descricao da Classificacao nao Informado.";
       $this->erro_campo = "t01_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t01_deprec == null ){ 
       $this->erro_sql = " Campo Percentual de Depreciacao nao Informado.";
       $this->erro_campo = "t01_deprec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t01_descr1 == null ){ 
       $this->erro_sql = " Campo Descricao 1 para o Bem nao Informado.";
       $this->erro_campo = "t01_descr1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t01_descr2 == null ){ 
       $this->erro_sql = " Campo Descricao 2 nao Informado.";
       $this->erro_campo = "t01_descr2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t01_descr3 == null ){ 
       $this->erro_sql = " Campo Descricao 3 nao Informado.";
       $this->erro_campo = "t01_descr3";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t01_descr4 == null ){ 
       $this->erro_sql = " Campo Descricao 4 nao Informado.";
       $this->erro_campo = "t01_descr4";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t01_descr5 == null ){ 
       $this->erro_sql = " Campo Descricao 5 nao Informado.";
       $this->erro_campo = "t01_descr5";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t01_descr6 == null ){ 
       $this->erro_sql = " Campo Descricao 6 nao Informado.";
       $this->erro_campo = "t01_descr6";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t01_plano == null ){ 
       $this->erro_sql = " Campo Plano de Contas nao Informado.";
       $this->erro_campo = "t01_plano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t01_ultcod == null ){ 
       $this->erro_sql = " Campo Numero do Ultimo codigo Cad. nao Informado.";
       $this->erro_campo = "t01_ultcod";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into clabens_ant(
                                       t01_class 
                                      ,t01_descr 
                                      ,t01_deprec 
                                      ,t01_descr1 
                                      ,t01_descr2 
                                      ,t01_descr3 
                                      ,t01_descr4 
                                      ,t01_descr5 
                                      ,t01_descr6 
                                      ,t01_plano 
                                      ,t01_ultcod 
                       )
                values (
                                '$this->t01_class' 
                               ,'$this->t01_descr' 
                               ,$this->t01_deprec 
                               ,'$this->t01_descr1' 
                               ,'$this->t01_descr2' 
                               ,'$this->t01_descr3' 
                               ,'$this->t01_descr4' 
                               ,'$this->t01_descr5' 
                               ,'$this->t01_descr6' 
                               ,'$this->t01_plano' 
                               ,$this->t01_ultcod 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de Classificacao de Bens () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de Classificacao de Bens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de Classificacao de Bens () nao Incluído. Inclusao Abortada.";
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
     $sql = " update clabens_ant set ";
     $virgula = "";
     if(trim($this->t01_class)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t01_class"])){ 
       $sql  .= $virgula." t01_class = '$this->t01_class' ";
       $virgula = ",";
       if(trim($this->t01_class) == null ){ 
         $this->erro_sql = " Campo Codigo de Classificacao do Bem nao Informado.";
         $this->erro_campo = "t01_class";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t01_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t01_descr"])){ 
       $sql  .= $virgula." t01_descr = '$this->t01_descr' ";
       $virgula = ",";
       if(trim($this->t01_descr) == null ){ 
         $this->erro_sql = " Campo Descricao da Classificacao nao Informado.";
         $this->erro_campo = "t01_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t01_deprec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t01_deprec"])){ 
       $sql  .= $virgula." t01_deprec = $this->t01_deprec ";
       $virgula = ",";
       if(trim($this->t01_deprec) == null ){ 
         $this->erro_sql = " Campo Percentual de Depreciacao nao Informado.";
         $this->erro_campo = "t01_deprec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t01_descr1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t01_descr1"])){ 
       $sql  .= $virgula." t01_descr1 = '$this->t01_descr1' ";
       $virgula = ",";
       if(trim($this->t01_descr1) == null ){ 
         $this->erro_sql = " Campo Descricao 1 para o Bem nao Informado.";
         $this->erro_campo = "t01_descr1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t01_descr2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t01_descr2"])){ 
       $sql  .= $virgula." t01_descr2 = '$this->t01_descr2' ";
       $virgula = ",";
       if(trim($this->t01_descr2) == null ){ 
         $this->erro_sql = " Campo Descricao 2 nao Informado.";
         $this->erro_campo = "t01_descr2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t01_descr3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t01_descr3"])){ 
       $sql  .= $virgula." t01_descr3 = '$this->t01_descr3' ";
       $virgula = ",";
       if(trim($this->t01_descr3) == null ){ 
         $this->erro_sql = " Campo Descricao 3 nao Informado.";
         $this->erro_campo = "t01_descr3";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t01_descr4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t01_descr4"])){ 
       $sql  .= $virgula." t01_descr4 = '$this->t01_descr4' ";
       $virgula = ",";
       if(trim($this->t01_descr4) == null ){ 
         $this->erro_sql = " Campo Descricao 4 nao Informado.";
         $this->erro_campo = "t01_descr4";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t01_descr5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t01_descr5"])){ 
       $sql  .= $virgula." t01_descr5 = '$this->t01_descr5' ";
       $virgula = ",";
       if(trim($this->t01_descr5) == null ){ 
         $this->erro_sql = " Campo Descricao 5 nao Informado.";
         $this->erro_campo = "t01_descr5";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t01_descr6)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t01_descr6"])){ 
       $sql  .= $virgula." t01_descr6 = '$this->t01_descr6' ";
       $virgula = ",";
       if(trim($this->t01_descr6) == null ){ 
         $this->erro_sql = " Campo Descricao 6 nao Informado.";
         $this->erro_campo = "t01_descr6";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t01_plano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t01_plano"])){ 
       $sql  .= $virgula." t01_plano = '$this->t01_plano' ";
       $virgula = ",";
       if(trim($this->t01_plano) == null ){ 
         $this->erro_sql = " Campo Plano de Contas nao Informado.";
         $this->erro_campo = "t01_plano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t01_ultcod)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t01_ultcod"])){ 
       $sql  .= $virgula." t01_ultcod = $this->t01_ultcod ";
       $virgula = ",";
       if(trim($this->t01_ultcod) == null ){ 
         $this->erro_sql = " Campo Numero do Ultimo codigo Cad. nao Informado.";
         $this->erro_campo = "t01_ultcod";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de Classificacao de Bens nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de Classificacao de Bens nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from clabens_ant
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
       $this->erro_sql   = "Tabela de Classificacao de Bens nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de Classificacao de Bens nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:clabens_ant";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>