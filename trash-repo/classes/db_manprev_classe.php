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
//CLASSE DA ENTIDADE manprev
class cl_manprev { 
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
   var $t10_bem = null; 
   var $t10_obs = null; 
   var $t10_dtagen_dia = null; 
   var $t10_dtagen_mes = null; 
   var $t10_dtagen_ano = null; 
   var $t10_dtagen = null; 
   var $t10_dtreal_dia = null; 
   var $t10_dtreal_mes = null; 
   var $t10_dtreal_ano = null; 
   var $t10_dtreal = null; 
   var $t10_numemp = null; 
   var $t10_numcgm = 0; 
   var $t10_ntfisc = null; 
   var $t10_valor = 0; 
   var $t10_garant_dia = null; 
   var $t10_garant_mes = null; 
   var $t10_garant_ano = null; 
   var $t10_garant = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t10_bem = char(    13) = Codigo do Bem 
                 t10_obs = char(    60) = Observacoes e Anotacoes 
                 t10_dtagen = date = Data Agendada/Limite 
                 t10_dtreal = date = Data da Realizacao da Manutenc 
                 t10_numemp = char(     8) = Numero do Empenho 
                 t10_numcgm = int4 = Numero CGM 
                 t10_ntfisc = char(    10) = Numero da Nota Fiscal 
                 t10_valor = float8 = Valor 
                 t10_garant = date = Garantia (em dias) 
                 ";
   //funcao construtor da classe 
   function cl_manprev() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("manprev"); 
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
       $this->t10_bem = ($this->t10_bem == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_bem"]:$this->t10_bem);
       $this->t10_obs = ($this->t10_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_obs"]:$this->t10_obs);
       if($this->t10_dtagen == ""){
         $this->t10_dtagen_dia = ($this->t10_dtagen_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_dtagen_dia"]:$this->t10_dtagen_dia);
         $this->t10_dtagen_mes = ($this->t10_dtagen_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_dtagen_mes"]:$this->t10_dtagen_mes);
         $this->t10_dtagen_ano = ($this->t10_dtagen_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_dtagen_ano"]:$this->t10_dtagen_ano);
         if($this->t10_dtagen_dia != ""){
            $this->t10_dtagen = $this->t10_dtagen_ano."-".$this->t10_dtagen_mes."-".$this->t10_dtagen_dia;
         }
       }
       if($this->t10_dtreal == ""){
         $this->t10_dtreal_dia = ($this->t10_dtreal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_dtreal_dia"]:$this->t10_dtreal_dia);
         $this->t10_dtreal_mes = ($this->t10_dtreal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_dtreal_mes"]:$this->t10_dtreal_mes);
         $this->t10_dtreal_ano = ($this->t10_dtreal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_dtreal_ano"]:$this->t10_dtreal_ano);
         if($this->t10_dtreal_dia != ""){
            $this->t10_dtreal = $this->t10_dtreal_ano."-".$this->t10_dtreal_mes."-".$this->t10_dtreal_dia;
         }
       }
       $this->t10_numemp = ($this->t10_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_numemp"]:$this->t10_numemp);
       $this->t10_numcgm = ($this->t10_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_numcgm"]:$this->t10_numcgm);
       $this->t10_ntfisc = ($this->t10_ntfisc == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_ntfisc"]:$this->t10_ntfisc);
       $this->t10_valor = ($this->t10_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_valor"]:$this->t10_valor);
       if($this->t10_garant == ""){
         $this->t10_garant_dia = ($this->t10_garant_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_garant_dia"]:$this->t10_garant_dia);
         $this->t10_garant_mes = ($this->t10_garant_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_garant_mes"]:$this->t10_garant_mes);
         $this->t10_garant_ano = ($this->t10_garant_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t10_garant_ano"]:$this->t10_garant_ano);
         if($this->t10_garant_dia != ""){
            $this->t10_garant = $this->t10_garant_ano."-".$this->t10_garant_mes."-".$this->t10_garant_dia;
         }
       }
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->t10_bem == null ){ 
       $this->erro_sql = " Campo Codigo do Bem nao Informado.";
       $this->erro_campo = "t10_bem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t10_obs == null ){ 
       $this->erro_sql = " Campo Observacoes e Anotacoes nao Informado.";
       $this->erro_campo = "t10_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t10_dtagen == null ){ 
       $this->erro_sql = " Campo Data Agendada/Limite nao Informado.";
       $this->erro_campo = "t10_dtagen_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t10_dtreal == null ){ 
       $this->erro_sql = " Campo Data da Realizacao da Manutenc nao Informado.";
       $this->erro_campo = "t10_dtreal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t10_numemp == null ){ 
       $this->erro_sql = " Campo Numero do Empenho nao Informado.";
       $this->erro_campo = "t10_numemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t10_numcgm == null ){ 
       $this->erro_sql = " Campo Numero CGM nao Informado.";
       $this->erro_campo = "t10_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t10_ntfisc == null ){ 
       $this->erro_sql = " Campo Numero da Nota Fiscal nao Informado.";
       $this->erro_campo = "t10_ntfisc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t10_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "t10_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t10_garant == null ){ 
       $this->erro_sql = " Campo Garantia (em dias) nao Informado.";
       $this->erro_campo = "t10_garant_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into manprev(
                                       t10_bem 
                                      ,t10_obs 
                                      ,t10_dtagen 
                                      ,t10_dtreal 
                                      ,t10_numemp 
                                      ,t10_numcgm 
                                      ,t10_ntfisc 
                                      ,t10_valor 
                                      ,t10_garant 
                       )
                values (
                                '$this->t10_bem' 
                               ,'$this->t10_obs' 
                               ,".($this->t10_dtagen == "null" || $this->t10_dtagen == ""?"null":"'".$this->t10_dtagen."'")." 
                               ,".($this->t10_dtreal == "null" || $this->t10_dtreal == ""?"null":"'".$this->t10_dtreal."'")." 
                               ,'$this->t10_numemp' 
                               ,$this->t10_numcgm 
                               ,'$this->t10_ntfisc' 
                               ,$this->t10_valor 
                               ,".($this->t10_garant == "null" || $this->t10_garant == ""?"null":"'".$this->t10_garant."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Manutencoes Preventivas                () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Manutencoes Preventivas                já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Manutencoes Preventivas                () nao Incluído. Inclusao Abortada.";
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
     $sql = " update manprev set ";
     $virgula = "";
     if(trim($this->t10_bem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t10_bem"])){ 
       $sql  .= $virgula." t10_bem = '$this->t10_bem' ";
       $virgula = ",";
       if(trim($this->t10_bem) == null ){ 
         $this->erro_sql = " Campo Codigo do Bem nao Informado.";
         $this->erro_campo = "t10_bem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t10_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t10_obs"])){ 
       $sql  .= $virgula." t10_obs = '$this->t10_obs' ";
       $virgula = ",";
       if(trim($this->t10_obs) == null ){ 
         $this->erro_sql = " Campo Observacoes e Anotacoes nao Informado.";
         $this->erro_campo = "t10_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t10_dtagen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t10_dtagen_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t10_dtagen_dia"] !="") ){ 
       $sql  .= $virgula." t10_dtagen = '$this->t10_dtagen' ";
       $virgula = ",";
       if(trim($this->t10_dtagen) == null ){ 
         $this->erro_sql = " Campo Data Agendada/Limite nao Informado.";
         $this->erro_campo = "t10_dtagen_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t10_dtagen_dia"])){ 
         $sql  .= $virgula." t10_dtagen = null ";
         $virgula = ",";
         if(trim($this->t10_dtagen) == null ){ 
           $this->erro_sql = " Campo Data Agendada/Limite nao Informado.";
           $this->erro_campo = "t10_dtagen_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t10_dtreal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t10_dtreal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t10_dtreal_dia"] !="") ){ 
       $sql  .= $virgula." t10_dtreal = '$this->t10_dtreal' ";
       $virgula = ",";
       if(trim($this->t10_dtreal) == null ){ 
         $this->erro_sql = " Campo Data da Realizacao da Manutenc nao Informado.";
         $this->erro_campo = "t10_dtreal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t10_dtreal_dia"])){ 
         $sql  .= $virgula." t10_dtreal = null ";
         $virgula = ",";
         if(trim($this->t10_dtreal) == null ){ 
           $this->erro_sql = " Campo Data da Realizacao da Manutenc nao Informado.";
           $this->erro_campo = "t10_dtreal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t10_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t10_numemp"])){ 
       $sql  .= $virgula." t10_numemp = '$this->t10_numemp' ";
       $virgula = ",";
       if(trim($this->t10_numemp) == null ){ 
         $this->erro_sql = " Campo Numero do Empenho nao Informado.";
         $this->erro_campo = "t10_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t10_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t10_numcgm"])){ 
       $sql  .= $virgula." t10_numcgm = $this->t10_numcgm ";
       $virgula = ",";
       if(trim($this->t10_numcgm) == null ){ 
         $this->erro_sql = " Campo Numero CGM nao Informado.";
         $this->erro_campo = "t10_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t10_ntfisc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t10_ntfisc"])){ 
       $sql  .= $virgula." t10_ntfisc = '$this->t10_ntfisc' ";
       $virgula = ",";
       if(trim($this->t10_ntfisc) == null ){ 
         $this->erro_sql = " Campo Numero da Nota Fiscal nao Informado.";
         $this->erro_campo = "t10_ntfisc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t10_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t10_valor"])){ 
       $sql  .= $virgula." t10_valor = $this->t10_valor ";
       $virgula = ",";
       if(trim($this->t10_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "t10_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t10_garant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t10_garant_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t10_garant_dia"] !="") ){ 
       $sql  .= $virgula." t10_garant = '$this->t10_garant' ";
       $virgula = ",";
       if(trim($this->t10_garant) == null ){ 
         $this->erro_sql = " Campo Garantia (em dias) nao Informado.";
         $this->erro_campo = "t10_garant_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t10_garant_dia"])){ 
         $sql  .= $virgula." t10_garant = null ";
         $virgula = ",";
         if(trim($this->t10_garant) == null ){ 
           $this->erro_sql = " Campo Garantia (em dias) nao Informado.";
           $this->erro_campo = "t10_garant_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Manutencoes Preventivas                nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Manutencoes Preventivas                nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from manprev
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
       $this->erro_sql   = "Cadastro de Manutencoes Preventivas                nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Manutencoes Preventivas                nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:manprev";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>