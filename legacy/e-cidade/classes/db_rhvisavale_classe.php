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
//CLASSE DA ENTIDADE rhvisavale
class cl_rhvisavale { 
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
   var $rh47_instit = 0; 
   var $rh47_rubric = null; 
   var $rh47_contrato = 0; 
   var $rh47_tipovale = 0; 
   var $rh47_perc = 0; 
   var $rh47_diasuteis = 0; 
   var $rh47_db_sysfuncoes = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh47_instit = int4 = codigo da instituicao 
                 rh47_rubric = varchar(4) = Código da Rubrica 
                 rh47_contrato = int8 = Número do contrato 
                 rh47_tipovale = int4 = Tipo de vale 
                 rh47_perc = float4 = Percentual no ponto 
                 rh47_diasuteis = int4 = Dias úteis 
                 rh47_db_sysfuncoes = int4 = Código Função 
                 ";
   //funcao construtor da classe 
   function cl_rhvisavale() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhvisavale"); 
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
       $this->rh47_instit = ($this->rh47_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh47_instit"]:$this->rh47_instit);
       $this->rh47_rubric = ($this->rh47_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh47_rubric"]:$this->rh47_rubric);
       $this->rh47_contrato = ($this->rh47_contrato == ""?@$GLOBALS["HTTP_POST_VARS"]["rh47_contrato"]:$this->rh47_contrato);
       $this->rh47_tipovale = ($this->rh47_tipovale == ""?@$GLOBALS["HTTP_POST_VARS"]["rh47_tipovale"]:$this->rh47_tipovale);
       $this->rh47_perc = ($this->rh47_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh47_perc"]:$this->rh47_perc);
       $this->rh47_diasuteis = ($this->rh47_diasuteis == ""?@$GLOBALS["HTTP_POST_VARS"]["rh47_diasuteis"]:$this->rh47_diasuteis);
       $this->rh47_db_sysfuncoes = ($this->rh47_db_sysfuncoes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh47_db_sysfuncoes"]:$this->rh47_db_sysfuncoes);
     }else{
       $this->rh47_instit = ($this->rh47_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh47_instit"]:$this->rh47_instit);
     }
   }
   // funcao para inclusao
   function incluir ($rh47_instit){ 
      $this->atualizacampos();
     if($this->rh47_rubric == null ){ 
       $this->erro_sql = " Campo Código da Rubrica nao Informado.";
       $this->erro_campo = "rh47_rubric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh47_contrato == null ){ 
       $this->erro_sql = " Campo Número do contrato nao Informado.";
       $this->erro_campo = "rh47_contrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh47_tipovale == null ){ 
       $this->erro_sql = " Campo Tipo de vale nao Informado.";
       $this->erro_campo = "rh47_tipovale";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh47_perc == null ){ 
       $this->erro_sql = " Campo Percentual no ponto nao Informado.";
       $this->erro_campo = "rh47_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh47_diasuteis == null ){ 
       $this->erro_sql = " Campo Dias úteis nao Informado.";
       $this->erro_campo = "rh47_diasuteis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh47_db_sysfuncoes == null ){ 
       $this->erro_sql = " Campo Código Função nao Informado.";
       $this->erro_campo = "rh47_db_sysfuncoes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh47_instit = $rh47_instit; 
     if(($this->rh47_instit == null) || ($this->rh47_instit == "") ){ 
       $this->erro_sql = " Campo rh47_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhvisavale(
                                       rh47_instit 
                                      ,rh47_rubric 
                                      ,rh47_contrato 
                                      ,rh47_tipovale 
                                      ,rh47_perc 
                                      ,rh47_diasuteis 
                                      ,rh47_db_sysfuncoes 
                       )
                values (
                                $this->rh47_instit 
                               ,'$this->rh47_rubric' 
                               ,$this->rh47_contrato 
                               ,$this->rh47_tipovale 
                               ,$this->rh47_perc 
                               ,$this->rh47_diasuteis 
                               ,$this->rh47_db_sysfuncoes 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro do contrato tipo de vale ($this->rh47_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro do contrato tipo de vale já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro do contrato tipo de vale ($this->rh47_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh47_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh47_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8628,'$this->rh47_instit','I')");
       $resac = db_query("insert into db_acount values($acount,1470,8628,'','".AddSlashes(pg_result($resaco,0,'rh47_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1470,8635,'','".AddSlashes(pg_result($resaco,0,'rh47_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1470,8629,'','".AddSlashes(pg_result($resaco,0,'rh47_contrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1470,8630,'','".AddSlashes(pg_result($resaco,0,'rh47_tipovale'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1470,8755,'','".AddSlashes(pg_result($resaco,0,'rh47_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1470,12502,'','".AddSlashes(pg_result($resaco,0,'rh47_diasuteis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1470,12594,'','".AddSlashes(pg_result($resaco,0,'rh47_db_sysfuncoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh47_instit=null) { 
      $this->atualizacampos();
     $sql = " update rhvisavale set ";
     $virgula = "";
     if(trim($this->rh47_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh47_instit"])){ 
       $sql  .= $virgula." rh47_instit = $this->rh47_instit ";
       $virgula = ",";
       if(trim($this->rh47_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "rh47_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh47_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh47_rubric"])){ 
       $sql  .= $virgula." rh47_rubric = '$this->rh47_rubric' ";
       $virgula = ",";
       if(trim($this->rh47_rubric) == null ){ 
         $this->erro_sql = " Campo Código da Rubrica nao Informado.";
         $this->erro_campo = "rh47_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh47_contrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh47_contrato"])){ 
       $sql  .= $virgula." rh47_contrato = $this->rh47_contrato ";
       $virgula = ",";
       if(trim($this->rh47_contrato) == null ){ 
         $this->erro_sql = " Campo Número do contrato nao Informado.";
         $this->erro_campo = "rh47_contrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh47_tipovale)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh47_tipovale"])){ 
       $sql  .= $virgula." rh47_tipovale = $this->rh47_tipovale ";
       $virgula = ",";
       if(trim($this->rh47_tipovale) == null ){ 
         $this->erro_sql = " Campo Tipo de vale nao Informado.";
         $this->erro_campo = "rh47_tipovale";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh47_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh47_perc"])){ 
       $sql  .= $virgula." rh47_perc = $this->rh47_perc ";
       $virgula = ",";
       if(trim($this->rh47_perc) == null ){ 
         $this->erro_sql = " Campo Percentual no ponto nao Informado.";
         $this->erro_campo = "rh47_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh47_diasuteis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh47_diasuteis"])){ 
       $sql  .= $virgula." rh47_diasuteis = $this->rh47_diasuteis ";
       $virgula = ",";
       if(trim($this->rh47_diasuteis) == null ){ 
         $this->erro_sql = " Campo Dias úteis nao Informado.";
         $this->erro_campo = "rh47_diasuteis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh47_db_sysfuncoes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh47_db_sysfuncoes"])){ 
       $sql  .= $virgula." rh47_db_sysfuncoes = $this->rh47_db_sysfuncoes ";
       $virgula = ",";
       if(trim($this->rh47_db_sysfuncoes) == null ){ 
         $this->erro_sql = " Campo Código Função nao Informado.";
         $this->erro_campo = "rh47_db_sysfuncoes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh47_instit!=null){
       $sql .= " rh47_instit = $this->rh47_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh47_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8628,'$this->rh47_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh47_instit"]))
           $resac = db_query("insert into db_acount values($acount,1470,8628,'".AddSlashes(pg_result($resaco,$conresaco,'rh47_instit'))."','$this->rh47_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh47_rubric"]))
           $resac = db_query("insert into db_acount values($acount,1470,8635,'".AddSlashes(pg_result($resaco,$conresaco,'rh47_rubric'))."','$this->rh47_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh47_contrato"]))
           $resac = db_query("insert into db_acount values($acount,1470,8629,'".AddSlashes(pg_result($resaco,$conresaco,'rh47_contrato'))."','$this->rh47_contrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh47_tipovale"]))
           $resac = db_query("insert into db_acount values($acount,1470,8630,'".AddSlashes(pg_result($resaco,$conresaco,'rh47_tipovale'))."','$this->rh47_tipovale',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh47_perc"]))
           $resac = db_query("insert into db_acount values($acount,1470,8755,'".AddSlashes(pg_result($resaco,$conresaco,'rh47_perc'))."','$this->rh47_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh47_diasuteis"]))
           $resac = db_query("insert into db_acount values($acount,1470,12502,'".AddSlashes(pg_result($resaco,$conresaco,'rh47_diasuteis'))."','$this->rh47_diasuteis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh47_db_sysfuncoes"]))
           $resac = db_query("insert into db_acount values($acount,1470,12594,'".AddSlashes(pg_result($resaco,$conresaco,'rh47_db_sysfuncoes'))."','$this->rh47_db_sysfuncoes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro do contrato tipo de vale nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh47_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro do contrato tipo de vale nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh47_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh47_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh47_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh47_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8628,'$rh47_instit','E')");
         $resac = db_query("insert into db_acount values($acount,1470,8628,'','".AddSlashes(pg_result($resaco,$iresaco,'rh47_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1470,8635,'','".AddSlashes(pg_result($resaco,$iresaco,'rh47_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1470,8629,'','".AddSlashes(pg_result($resaco,$iresaco,'rh47_contrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1470,8630,'','".AddSlashes(pg_result($resaco,$iresaco,'rh47_tipovale'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1470,8755,'','".AddSlashes(pg_result($resaco,$iresaco,'rh47_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1470,12502,'','".AddSlashes(pg_result($resaco,$iresaco,'rh47_diasuteis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1470,12594,'','".AddSlashes(pg_result($resaco,$iresaco,'rh47_db_sysfuncoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhvisavale
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh47_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh47_instit = $rh47_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro do contrato tipo de vale nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh47_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro do contrato tipo de vale nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh47_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh47_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhvisavale";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh47_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhvisavale ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhvisavale.rh47_instit";
     $sql .= "      inner join db_sysfuncoes  on  db_sysfuncoes.codfuncao = rhvisavale.rh47_db_sysfuncoes";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhvisavale.rh47_rubric and  rhrubricas.rh27_instit = rhvisavale.rh47_instit";
     /*
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = rhrubricas.rh27_instit";
    */
    $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql2 = "";
     if($dbwhere==""){
       if($rh47_instit!=null ){
         $sql2 .= " where rhvisavale.rh47_instit = $rh47_instit "; 
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
   function sql_query_file ( $rh47_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhvisavale ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh47_instit!=null ){
         $sql2 .= " where rhvisavale.rh47_instit = $rh47_instit "; 
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
   function sql_query_valcgm ( $rh47_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhvisavale ";
     $sql .= "      inner join rhvisavalecgm  on  rhvisavalecgm.rh48_instit = rhvisavale.rh47_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($rh47_instit!=null ){
         $sql2 .= " where rhvisavale.rh47_instit = $rh47_instit "; 
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
}
?>