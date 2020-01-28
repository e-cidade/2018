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
//CLASSE DA ENTIDADE rhcfpessrub
class cl_rhcfpessrub { 
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
   var $rh23_rubmat = null; 
   var $rh23_rubdec = null; 
   var $rh23_palime = null; 
   var $rh23_ferias = null; 
   var $rh23_fer13 = null; 
   var $rh23_ferant = null; 
   var $rh23_fer13o = null; 
   var $rh23_fer13a = null; 
   var $rh23_ferabo = null; 
   var $rh23_feabot = null; 
   var $rh23_feradi = null; 
   var $rh23_fadiab = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh23_rubmat = varchar(4) = Rubrica do Salário Maternidade 
                 rh23_rubdec = varchar(4) = Rubrica do Adiantamento de 13. 
                 rh23_palime = varchar(4) = Rubrica Pensão Alimentícia 
                 rh23_ferias = varchar(4) = Férias 
                 rh23_fer13 = varchar(4) = 1/3 de férias 
                 rh23_ferant = varchar(4) = Férias mês anterior 
                 rh23_fer13o = varchar(4) = 1/3 de férias 
                 rh23_fer13a = varchar(4) = 1/3 s/ abono de férias 
                 rh23_ferabo = varchar(4) = Abono de férias 
                 rh23_feabot = varchar(4) = Abono Mês Anterior 
                 rh23_feradi = varchar(4) = Adiantamento de férias 
                 rh23_fadiab = varchar(4) = Adiantamento s/abono de férias 
                 ";
   //funcao construtor da classe 
   function cl_rhcfpessrub() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhcfpessrub"); 
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
       $this->rh23_rubmat = ($this->rh23_rubmat == ""?@$GLOBALS["HTTP_POST_VARS"]["rh23_rubmat"]:$this->rh23_rubmat);
       $this->rh23_rubdec = ($this->rh23_rubdec == ""?@$GLOBALS["HTTP_POST_VARS"]["rh23_rubdec"]:$this->rh23_rubdec);
       $this->rh23_palime = ($this->rh23_palime == ""?@$GLOBALS["HTTP_POST_VARS"]["rh23_palime"]:$this->rh23_palime);
       $this->rh23_ferias = ($this->rh23_ferias == ""?@$GLOBALS["HTTP_POST_VARS"]["rh23_ferias"]:$this->rh23_ferias);
       $this->rh23_fer13 = ($this->rh23_fer13 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh23_fer13"]:$this->rh23_fer13);
       $this->rh23_ferant = ($this->rh23_ferant == ""?@$GLOBALS["HTTP_POST_VARS"]["rh23_ferant"]:$this->rh23_ferant);
       $this->rh23_fer13o = ($this->rh23_fer13o == ""?@$GLOBALS["HTTP_POST_VARS"]["rh23_fer13o"]:$this->rh23_fer13o);
       $this->rh23_fer13a = ($this->rh23_fer13a == ""?@$GLOBALS["HTTP_POST_VARS"]["rh23_fer13a"]:$this->rh23_fer13a);
       $this->rh23_ferabo = ($this->rh23_ferabo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh23_ferabo"]:$this->rh23_ferabo);
       $this->rh23_feabot = ($this->rh23_feabot == ""?@$GLOBALS["HTTP_POST_VARS"]["rh23_feabot"]:$this->rh23_feabot);
       $this->rh23_feradi = ($this->rh23_feradi == ""?@$GLOBALS["HTTP_POST_VARS"]["rh23_feradi"]:$this->rh23_feradi);
       $this->rh23_fadiab = ($this->rh23_fadiab == ""?@$GLOBALS["HTTP_POST_VARS"]["rh23_fadiab"]:$this->rh23_fadiab);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->rh23_rubmat == null ){ 
       $this->erro_sql = " Campo Rubrica do Salário Maternidade nao Informado.";
       $this->erro_campo = "rh23_rubmat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh23_rubdec == null ){ 
       $this->erro_sql = " Campo Rubrica do Adiantamento de 13. nao Informado.";
       $this->erro_campo = "rh23_rubdec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh23_palime == null ){ 
       $this->erro_sql = " Campo Rubrica Pensão Alimentícia nao Informado.";
       $this->erro_campo = "rh23_palime";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh23_ferias == null ){ 
       $this->erro_sql = " Campo Férias nao Informado.";
       $this->erro_campo = "rh23_ferias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh23_fer13 == null ){ 
       $this->erro_sql = " Campo 1/3 de férias nao Informado.";
       $this->erro_campo = "rh23_fer13";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh23_ferant == null ){ 
       $this->erro_sql = " Campo Férias mês anterior nao Informado.";
       $this->erro_campo = "rh23_ferant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh23_fer13o == null ){ 
       $this->erro_sql = " Campo 1/3 de férias nao Informado.";
       $this->erro_campo = "rh23_fer13o";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh23_fer13a == null ){ 
       $this->erro_sql = " Campo 1/3 s/ abono de férias nao Informado.";
       $this->erro_campo = "rh23_fer13a";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh23_ferabo == null ){ 
       $this->erro_sql = " Campo Abono de férias nao Informado.";
       $this->erro_campo = "rh23_ferabo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh23_feabot == null ){ 
       $this->erro_sql = " Campo Abono Mês Anterior nao Informado.";
       $this->erro_campo = "rh23_feabot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh23_feradi == null ){ 
       $this->erro_sql = " Campo Adiantamento de férias nao Informado.";
       $this->erro_campo = "rh23_feradi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh23_fadiab == null ){ 
       $this->erro_sql = " Campo Adiantamento s/abono de férias nao Informado.";
       $this->erro_campo = "rh23_fadiab";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhcfpessrub(
                                       rh23_rubmat 
                                      ,rh23_rubdec 
                                      ,rh23_palime 
                                      ,rh23_ferias 
                                      ,rh23_fer13 
                                      ,rh23_ferant 
                                      ,rh23_fer13o 
                                      ,rh23_fer13a 
                                      ,rh23_ferabo 
                                      ,rh23_feabot 
                                      ,rh23_feradi 
                                      ,rh23_fadiab 
                       )
                values (
                                '$this->rh23_rubmat' 
                               ,'$this->rh23_rubdec' 
                               ,'$this->rh23_palime' 
                               ,'$this->rh23_ferias' 
                               ,'$this->rh23_fer13' 
                               ,'$this->rh23_ferant' 
                               ,'$this->rh23_fer13o' 
                               ,'$this->rh23_fer13a' 
                               ,'$this->rh23_ferabo' 
                               ,'$this->rh23_feabot' 
                               ,'$this->rh23_feradi' 
                               ,'$this->rh23_fadiab' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Rubricas do CFPESS () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Rubricas do CFPESS já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Rubricas do CFPESS () nao Incluído. Inclusao Abortada.";
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
     $sql = " update rhcfpessrub set ";
     $virgula = "";
     if(trim($this->rh23_rubmat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh23_rubmat"])){ 
       $sql  .= $virgula." rh23_rubmat = '$this->rh23_rubmat' ";
       $virgula = ",";
       if(trim($this->rh23_rubmat) == null ){ 
         $this->erro_sql = " Campo Rubrica do Salário Maternidade nao Informado.";
         $this->erro_campo = "rh23_rubmat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh23_rubdec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh23_rubdec"])){ 
       $sql  .= $virgula." rh23_rubdec = '$this->rh23_rubdec' ";
       $virgula = ",";
       if(trim($this->rh23_rubdec) == null ){ 
         $this->erro_sql = " Campo Rubrica do Adiantamento de 13. nao Informado.";
         $this->erro_campo = "rh23_rubdec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh23_palime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh23_palime"])){ 
       $sql  .= $virgula." rh23_palime = '$this->rh23_palime' ";
       $virgula = ",";
       if(trim($this->rh23_palime) == null ){ 
         $this->erro_sql = " Campo Rubrica Pensão Alimentícia nao Informado.";
         $this->erro_campo = "rh23_palime";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh23_ferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh23_ferias"])){ 
       $sql  .= $virgula." rh23_ferias = '$this->rh23_ferias' ";
       $virgula = ",";
       if(trim($this->rh23_ferias) == null ){ 
         $this->erro_sql = " Campo Férias nao Informado.";
         $this->erro_campo = "rh23_ferias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh23_fer13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh23_fer13"])){ 
       $sql  .= $virgula." rh23_fer13 = '$this->rh23_fer13' ";
       $virgula = ",";
       if(trim($this->rh23_fer13) == null ){ 
         $this->erro_sql = " Campo 1/3 de férias nao Informado.";
         $this->erro_campo = "rh23_fer13";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh23_ferant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh23_ferant"])){ 
       $sql  .= $virgula." rh23_ferant = '$this->rh23_ferant' ";
       $virgula = ",";
       if(trim($this->rh23_ferant) == null ){ 
         $this->erro_sql = " Campo Férias mês anterior nao Informado.";
         $this->erro_campo = "rh23_ferant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh23_fer13o)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh23_fer13o"])){ 
       $sql  .= $virgula." rh23_fer13o = '$this->rh23_fer13o' ";
       $virgula = ",";
       if(trim($this->rh23_fer13o) == null ){ 
         $this->erro_sql = " Campo 1/3 de férias nao Informado.";
         $this->erro_campo = "rh23_fer13o";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh23_fer13a)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh23_fer13a"])){ 
       $sql  .= $virgula." rh23_fer13a = '$this->rh23_fer13a' ";
       $virgula = ",";
       if(trim($this->rh23_fer13a) == null ){ 
         $this->erro_sql = " Campo 1/3 s/ abono de férias nao Informado.";
         $this->erro_campo = "rh23_fer13a";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh23_ferabo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh23_ferabo"])){ 
       $sql  .= $virgula." rh23_ferabo = '$this->rh23_ferabo' ";
       $virgula = ",";
       if(trim($this->rh23_ferabo) == null ){ 
         $this->erro_sql = " Campo Abono de férias nao Informado.";
         $this->erro_campo = "rh23_ferabo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh23_feabot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh23_feabot"])){ 
       $sql  .= $virgula." rh23_feabot = '$this->rh23_feabot' ";
       $virgula = ",";
       if(trim($this->rh23_feabot) == null ){ 
         $this->erro_sql = " Campo Abono Mês Anterior nao Informado.";
         $this->erro_campo = "rh23_feabot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh23_feradi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh23_feradi"])){ 
       $sql  .= $virgula." rh23_feradi = '$this->rh23_feradi' ";
       $virgula = ",";
       if(trim($this->rh23_feradi) == null ){ 
         $this->erro_sql = " Campo Adiantamento de férias nao Informado.";
         $this->erro_campo = "rh23_feradi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh23_fadiab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh23_fadiab"])){ 
       $sql  .= $virgula." rh23_fadiab = '$this->rh23_fadiab' ";
       $virgula = ",";
       if(trim($this->rh23_fadiab) == null ){ 
         $this->erro_sql = " Campo Adiantamento s/abono de férias nao Informado.";
         $this->erro_campo = "rh23_fadiab";
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
       $this->erro_sql   = "Rubricas do CFPESS nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Rubricas do CFPESS nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from rhcfpessrub
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
       $this->erro_sql   = "Rubricas do CFPESS nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Rubricas do CFPESS nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:rhcfpessrub";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>