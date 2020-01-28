<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: caixa
//CLASSE DA ENTIDADE arrebanco
class cl_arrebanco { 
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
   var $k00_numpre = 0; 
   var $k00_numpar = 0; 
   var $k00_codbco = 0; 
   var $k00_codage = null; 
   var $k00_numbco = null; 
   var $k00_nbant = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k00_numpre = int4 = Numpre 
                 k00_numpar = int4 = Parcela 
                 k00_codbco = int4 = codigo do banco 
                 k00_codage = char(5) = codigo da agencia 
                 k00_numbco = varchar(15) = numero do banco 
                 k00_nbant = varchar(30) = numero do banco antigo 
                 ";
   //funcao construtor da classe 
   function cl_arrebanco() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arrebanco"); 
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
       $this->k00_numpre = ($this->k00_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpre"]:$this->k00_numpre);
       $this->k00_numpar = ($this->k00_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpar"]:$this->k00_numpar);
       $this->k00_codbco = ($this->k00_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_codbco"]:$this->k00_codbco);
       $this->k00_codage = ($this->k00_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_codage"]:$this->k00_codage);
       $this->k00_numbco = ($this->k00_numbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numbco"]:$this->k00_numbco);
       $this->k00_nbant = ($this->k00_nbant == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_nbant"]:$this->k00_nbant);
     }else{
       $this->k00_numpre = ($this->k00_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpre"]:$this->k00_numpre);
       $this->k00_numpar = ($this->k00_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpar"]:$this->k00_numpar);
       $this->k00_codbco = ($this->k00_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_codbco"]:$this->k00_codbco);
       $this->k00_codage = ($this->k00_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_codage"]:$this->k00_codage);
       $this->k00_numbco = ($this->k00_numbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numbco"]:$this->k00_numbco);
     }
   }
   // funcao para inclusao
   function incluir ($k00_numpre,$k00_numpar,$k00_codbco,$k00_codage,$k00_numbco){ 
      $this->atualizacampos();
     if($this->k00_nbant == null ){ 
       $this->erro_sql = " Campo numero do banco antigo nao Informado.";
       $this->erro_campo = "k00_nbant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k00_numpre = $k00_numpre; 
       $this->k00_numpar = $k00_numpar; 
       $this->k00_codbco = $k00_codbco; 
       $this->k00_codage = $k00_codage; 
       $this->k00_numbco = $k00_numbco; 
     if(($this->k00_numpre == null) || ($this->k00_numpre == "") ){ 
       $this->erro_sql = " Campo k00_numpre nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k00_numpar == null) || ($this->k00_numpar == "") ){ 
       $this->erro_sql = " Campo k00_numpar nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k00_codbco == null) || ($this->k00_codbco == "") ){ 
       $this->erro_sql = " Campo k00_codbco nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k00_codage == null) || ($this->k00_codage == "") ){ 
       $this->erro_sql = " Campo k00_codage nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k00_numbco == null) || ($this->k00_numbco == "") ){ 
       $this->erro_sql = " Campo k00_numbco nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arrebanco(
                                       k00_numpre 
                                      ,k00_numpar 
                                      ,k00_codbco 
                                      ,k00_codage 
                                      ,k00_numbco 
                                      ,k00_nbant 
                       )
                values (
                                $this->k00_numpre 
                               ,$this->k00_numpar 
                               ,$this->k00_codbco 
                               ,'$this->k00_codage' 
                               ,'$this->k00_numbco' 
                               ,'$this->k00_nbant' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->k00_numpre."-".$this->k00_numpar."-".$this->k00_codbco."-".$this->k00_codage."-".$this->k00_numbco) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->k00_numpre."-".$this->k00_numpar."-".$this->k00_codbco."-".$this->k00_codage."-".$this->k00_numbco) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_numpre."-".$this->k00_numpar."-".$this->k00_codbco."-".$this->k00_codage."-".$this->k00_numbco;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k00_numpre,$this->k00_numpar,$this->k00_codbco,$this->k00_codage,$this->k00_numbco));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,361,'$this->k00_numpre','I')");
       $resac = db_query("insert into db_acountkey values($acount,362,'$this->k00_numpar','I')");
       $resac = db_query("insert into db_acountkey values($acount,363,'$this->k00_codbco','I')");
       $resac = db_query("insert into db_acountkey values($acount,364,'$this->k00_codage','I')");
       $resac = db_query("insert into db_acountkey values($acount,365,'$this->k00_numbco','I')");
       $resac = db_query("insert into db_acount values($acount,71,361,'','".AddSlashes(pg_result($resaco,0,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,71,362,'','".AddSlashes(pg_result($resaco,0,'k00_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,71,363,'','".AddSlashes(pg_result($resaco,0,'k00_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,71,364,'','".AddSlashes(pg_result($resaco,0,'k00_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,71,365,'','".AddSlashes(pg_result($resaco,0,'k00_numbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,71,366,'','".AddSlashes(pg_result($resaco,0,'k00_nbant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k00_numpre=null,$k00_numpar=null,$k00_codbco=null,$k00_codage=null,$k00_numbco=null) { 
      $this->atualizacampos();
     $sql = " update arrebanco set ";
     $virgula = "";
     if(trim($this->k00_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"])){ 
       $sql  .= $virgula." k00_numpre = $this->k00_numpre ";
       $virgula = ",";
       if(trim($this->k00_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k00_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"])){ 
       $sql  .= $virgula." k00_numpar = $this->k00_numpar ";
       $virgula = ",";
       if(trim($this->k00_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k00_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_codbco"])){ 
       $sql  .= $virgula." k00_codbco = $this->k00_codbco ";
       $virgula = ",";
       if(trim($this->k00_codbco) == null ){ 
         $this->erro_sql = " Campo codigo do banco nao Informado.";
         $this->erro_campo = "k00_codbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_codage"])){ 
       $sql  .= $virgula." k00_codage = '$this->k00_codage' ";
       $virgula = ",";
       if(trim($this->k00_codage) == null ){ 
         $this->erro_sql = " Campo codigo da agencia nao Informado.";
         $this->erro_campo = "k00_codage";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numbco"])){ 
       $sql  .= $virgula." k00_numbco = '$this->k00_numbco' ";
       $virgula = ",";
     }
     if(trim($this->k00_nbant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_nbant"])){ 
       $sql  .= $virgula." k00_nbant = '$this->k00_nbant' ";
       $virgula = ",";
       if(trim($this->k00_nbant) == null ){ 
         $this->erro_sql = " Campo numero do banco antigo nao Informado.";
         $this->erro_campo = "k00_nbant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k00_numpre!=null){
       $sql .= " k00_numpre = $this->k00_numpre";
     }
     if($k00_numpar!=null){
       $sql .= " and  k00_numpar = $this->k00_numpar";
     }
     if($k00_codbco!=null){
       $sql .= " and  k00_codbco = $this->k00_codbco";
     }
     if($k00_codage!=null){
       $sql .= " and  k00_codage = '$this->k00_codage'";
     }
     if($k00_numbco!=null){
       $sql .= " and  k00_numbco = '$this->k00_numbco'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k00_numpre,$this->k00_numpar,$this->k00_codbco,$this->k00_codage,$this->k00_numbco));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,361,'$this->k00_numpre','A')");
         $resac = db_query("insert into db_acountkey values($acount,362,'$this->k00_numpar','A')");
         $resac = db_query("insert into db_acountkey values($acount,363,'$this->k00_codbco','A')");
         $resac = db_query("insert into db_acountkey values($acount,364,'$this->k00_codage','A')");
         $resac = db_query("insert into db_acountkey values($acount,365,'$this->k00_numbco','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"]) || $this->k00_numpre != "")
           $resac = db_query("insert into db_acount values($acount,71,361,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpre'))."','$this->k00_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"]) || $this->k00_numpar != "")
           $resac = db_query("insert into db_acount values($acount,71,362,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpar'))."','$this->k00_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_codbco"]) || $this->k00_codbco != "")
           $resac = db_query("insert into db_acount values($acount,71,363,'".AddSlashes(pg_result($resaco,$conresaco,'k00_codbco'))."','$this->k00_codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_codage"]) || $this->k00_codage != "")
           $resac = db_query("insert into db_acount values($acount,71,364,'".AddSlashes(pg_result($resaco,$conresaco,'k00_codage'))."','$this->k00_codage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numbco"]) || $this->k00_numbco != "")
           $resac = db_query("insert into db_acount values($acount,71,365,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numbco'))."','$this->k00_numbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_nbant"]) || $this->k00_nbant != "")
           $resac = db_query("insert into db_acount values($acount,71,366,'".AddSlashes(pg_result($resaco,$conresaco,'k00_nbant'))."','$this->k00_nbant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_numpre."-".$this->k00_numpar."-".$this->k00_codbco."-".$this->k00_codage."-".$this->k00_numbco;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_numpre."-".$this->k00_numpar."-".$this->k00_codbco."-".$this->k00_codage."-".$this->k00_numbco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_numpre."-".$this->k00_numpar."-".$this->k00_codbco."-".$this->k00_codage."-".$this->k00_numbco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k00_numpre=null,$k00_numpar=null,$k00_codbco=null,$k00_codage=null,$k00_numbco=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k00_numpre,$k00_numpar,$k00_codbco,$k00_codage,$k00_numbco));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,361,'$k00_numpre','E')");
         $resac = db_query("insert into db_acountkey values($acount,362,'$k00_numpar','E')");
         $resac = db_query("insert into db_acountkey values($acount,363,'$k00_codbco','E')");
         $resac = db_query("insert into db_acountkey values($acount,364,'$k00_codage','E')");
         $resac = db_query("insert into db_acountkey values($acount,365,'$k00_numbco','E')");
         $resac = db_query("insert into db_acount values($acount,71,361,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,71,362,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,71,363,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,71,364,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,71,365,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,71,366,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_nbant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from arrebanco
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k00_numpre != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k00_numpre = $k00_numpre ";
        }
        if($k00_numpar != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k00_numpar = $k00_numpar ";
        }
        if($k00_codbco != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k00_codbco = $k00_codbco ";
        }
        if($k00_codage != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k00_codage = '$k00_codage' ";
        }
        if($k00_numbco != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k00_numbco = '$k00_numbco' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k00_numpre."-".$k00_numpar."-".$k00_codbco."-".$k00_codage."-".$k00_numbco;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k00_numpre."-".$k00_numpar."-".$k00_codbco."-".$k00_codage."-".$k00_numbco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k00_numpre."-".$k00_numpar."-".$k00_codbco."-".$k00_codage."-".$k00_numbco;
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
        $this->erro_sql   = "Record Vazio na Tabela:arrebanco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k00_numpre=null,$k00_numpar=null,$k00_codbco=null,$k00_codage=null,$k00_numbco=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from arrebanco ";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_numpre!=null ){
         $sql2 .= " where arrebanco.k00_numpre = $k00_numpre "; 
       } 
       if($k00_numpar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " arrebanco.k00_numpar = $k00_numpar "; 
       } 
       if($k00_codbco!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " arrebanco.k00_codbco = $k00_codbco "; 
       } 
       if($k00_codage!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " arrebanco.k00_codage = '$k00_codage' "; 
       } 
       if($k00_numbco!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " arrebanco.k00_numbco = '$k00_numbco' "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $k00_numpre=null,$k00_numpar=null,$k00_codbco=null,$k00_codage=null,$k00_numbco=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from arrebanco ";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_numpre!=null ){
         $sql2 .= " where arrebanco.k00_numpre = $k00_numpre "; 
       } 
       if($k00_numpar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " arrebanco.k00_numpar = $k00_numpar "; 
       } 
       if($k00_codbco!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " arrebanco.k00_codbco = $k00_codbco "; 
       } 
       if($k00_codage!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " arrebanco.k00_codage = '$k00_codage' "; 
       } 
       if($k00_numbco!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " arrebanco.k00_numbco = '$k00_numbco' "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
  
  /**
   *
   * funcao para retornar o sql para integração do tj via webservice
   * @return string sql
   */
  function sql_queryRecibo ($iNumpre = ""){
  
    $sSql  = "select distinct                                                             \n";
    $sSql .= "       recibo.k00_numcgm,                                                   \n";
    $sSql .= "       recibo.k00_dtoper,                                                   \n";
    $sSql .= "       recibo.k00_dtvenc as k00_dtpaga,                                     \n";
    $sSql .= "       recibo.k00_numpre,                                                   \n";
    $sSql .= "       recibo.k00_numpar,                                                   \n";
    $sSql .= "       recibo.k00_numtot,                                                   \n";
    $sSql .= "       recibo.k00_numdig,                                                   \n";
    $sSql .= "       1::integer as tipo_emissao,                                          \n";
    $sSql .= "       arrehist.k00_histtxt,                                                \n";
    $sSql .= "       0::varchar as k00_numbco,                                            \n";
    $sSql .= "       recibo.k00_numnov,                                                   \n";
    $sSql .= "       0::integer as k00_conta                                              \n";
    $sSql .= "  from recibo                                                               \n";
    $sSql .= "       left join arrehist on arrehist.k00_numpre = recibo.k00_hist          \n";
    $sSql .= " where recibo.k00_numpre = {$iNumpre}                                       \n";
    $sSql .= " union                                                                      \n";
    $sSql .= "select distinct                                                             \n";
    $sSql .= "       recibopaga.k00_numcgm,                                               \n";
    $sSql .= "       recibopaga.k00_dtoper,                                               \n";
    $sSql .= "       recibopaga.k00_dtpaga,                                               \n";
    $sSql .= "       recibopaga.k00_numpre,                                               \n";
    $sSql .= "       recibopaga.k00_numpar,                                               \n";
    $sSql .= "       recibopaga.k00_numtot,                                               \n";
    $sSql .= "       recibopaga.k00_numdig,                                               \n";
    $sSql .= "       2::integer as tipo_emissao,                                          \n";
    $sSql .= "       arrehist.k00_histtxt,                                                \n";
    $sSql .= "       arrebanco.k00_numbco,                                                \n";
    $sSql .= "       recibopaga.k00_numnov,                                               \n";
    $sSql .= "       recibopaga.k00_conta                                                 \n";
    $sSql .= "  from recibopaga                                                           \n";
    $sSql .= "       left join arrehist  on arrehist.k00_numpre   = recibopaga.k00_hist   \n";
    $sSql .= "       left join arrebanco on arrebanco.k00_numpre  = recibopaga.k00_numnov \n";
    $sSql .= " where recibopaga.k00_numnov = {$iNumpre}                                   \n";
    return $sSql;
    
  }
  
}

?>