<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE rhpespadrao
class cl_rhpespadrao { 
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
   var $rh03_seqpes = 0; 
   var $rh03_anousu = 0; 
   var $rh03_mesusu = 0; 
   var $rh03_padrao = null; 
   var $rh03_padraoprev = null; 
   var $rh03_regime = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh03_seqpes = int4 = Sequência 
                 rh03_anousu = int4 = Ano do exercício 
                 rh03_mesusu = int4 = Mês do exercício 
                 rh03_padrao = char(10) = Padrão 
                 rh03_padraoprev = varchar(10) = Padrão de Previdência 
                 rh03_regime = int4 = Regime 
                 ";
   //funcao construtor da classe 
   function cl_rhpespadrao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpespadrao"); 
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
       $this->rh03_seqpes = ($this->rh03_seqpes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh03_seqpes"]:$this->rh03_seqpes);
       $this->rh03_anousu = ($this->rh03_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh03_anousu"]:$this->rh03_anousu);
       $this->rh03_mesusu = ($this->rh03_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh03_mesusu"]:$this->rh03_mesusu);
       $this->rh03_padrao = ($this->rh03_padrao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh03_padrao"]:$this->rh03_padrao);
       $this->rh03_padraoprev = ($this->rh03_padraoprev == ""?@$GLOBALS["HTTP_POST_VARS"]["rh03_padraoprev"]:$this->rh03_padraoprev);
       $this->rh03_regime = ($this->rh03_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["rh03_regime"]:$this->rh03_regime);
     }else{
       $this->rh03_seqpes = ($this->rh03_seqpes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh03_seqpes"]:$this->rh03_seqpes);
     }
   }
   // funcao para inclusao
   function incluir ($rh03_seqpes){ 
      $this->atualizacampos();
     if($this->rh03_anousu == null ){ 
       $this->erro_sql = " Campo Ano do exercício não informado.";
       $this->erro_campo = "rh03_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh03_mesusu == null ){ 
       $this->erro_sql = " Campo Mês do exercício não informado.";
       $this->erro_campo = "rh03_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh03_padrao == null ){ 
       $this->erro_sql = " Campo Padrão não informado.";
       $this->erro_campo = "rh03_padrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh03_regime == null ){ 
       $this->erro_sql = " Campo Regime não informado.";
       $this->erro_campo = "rh03_regime";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh03_seqpes = $rh03_seqpes; 
     if(($this->rh03_seqpes == null) || ($this->rh03_seqpes == "") ){ 
       $this->erro_sql = " Campo rh03_seqpes nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpespadrao(
                                       rh03_seqpes 
                                      ,rh03_anousu 
                                      ,rh03_mesusu 
                                      ,rh03_padrao 
                                      ,rh03_padraoprev 
                                      ,rh03_regime 
                       )
                values (
                                $this->rh03_seqpes 
                               ,$this->rh03_anousu 
                               ,$this->rh03_mesusu 
                               ,'$this->rh03_padrao' 
                               ,'$this->rh03_padraoprev' 
                               ,$this->rh03_regime 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "padrao do funcionário ($this->rh03_seqpes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "padrao do funcionário já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "padrao do funcionário ($this->rh03_seqpes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh03_seqpes;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh03_seqpes  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7039,'$this->rh03_seqpes','I')");
         $resac = db_query("insert into db_acount values($acount,1159,7039,'','".AddSlashes(pg_result($resaco,0,'rh03_seqpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1159,7643,'','".AddSlashes(pg_result($resaco,0,'rh03_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1159,7644,'','".AddSlashes(pg_result($resaco,0,'rh03_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1159,7040,'','".AddSlashes(pg_result($resaco,0,'rh03_padrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1159,20897,'','".AddSlashes(pg_result($resaco,0,'rh03_padraoprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1159,7642,'','".AddSlashes(pg_result($resaco,0,'rh03_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh03_seqpes=null) { 
      $this->atualizacampos();
     $sql = " update rhpespadrao set ";
     $virgula = "";
     if(trim($this->rh03_seqpes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh03_seqpes"])){ 
       $sql  .= $virgula." rh03_seqpes = $this->rh03_seqpes ";
       $virgula = ",";
       if(trim($this->rh03_seqpes) == null ){ 
         $this->erro_sql = " Campo Sequência não informado.";
         $this->erro_campo = "rh03_seqpes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh03_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh03_anousu"])){ 
       $sql  .= $virgula." rh03_anousu = $this->rh03_anousu ";
       $virgula = ",";
       if(trim($this->rh03_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do exercício não informado.";
         $this->erro_campo = "rh03_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh03_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh03_mesusu"])){ 
       $sql  .= $virgula." rh03_mesusu = $this->rh03_mesusu ";
       $virgula = ",";
       if(trim($this->rh03_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês do exercício não informado.";
         $this->erro_campo = "rh03_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh03_padrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh03_padrao"])){ 
       $sql  .= $virgula." rh03_padrao = '$this->rh03_padrao' ";
       $virgula = ",";
       if(trim($this->rh03_padrao) == null ){ 
         $this->erro_sql = " Campo Padrão não informado.";
         $this->erro_campo = "rh03_padrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh03_padraoprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh03_padraoprev"])){ 
       $sql  .= $virgula." rh03_padraoprev = '$this->rh03_padraoprev' ";
       $virgula = ",";
     }
     if(trim($this->rh03_regime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh03_regime"])){ 
       $sql  .= $virgula." rh03_regime = $this->rh03_regime ";
       $virgula = ",";
       if(trim($this->rh03_regime) == null ){ 
         $this->erro_sql = " Campo Regime não informado.";
         $this->erro_campo = "rh03_regime";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh03_seqpes!=null){
       $sql .= " rh03_seqpes = $this->rh03_seqpes";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh03_seqpes));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,7039,'$this->rh03_seqpes','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh03_seqpes"]) || $this->rh03_seqpes != "")
             $resac = db_query("insert into db_acount values($acount,1159,7039,'".AddSlashes(pg_result($resaco,$conresaco,'rh03_seqpes'))."','$this->rh03_seqpes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh03_anousu"]) || $this->rh03_anousu != "")
             $resac = db_query("insert into db_acount values($acount,1159,7643,'".AddSlashes(pg_result($resaco,$conresaco,'rh03_anousu'))."','$this->rh03_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh03_mesusu"]) || $this->rh03_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,1159,7644,'".AddSlashes(pg_result($resaco,$conresaco,'rh03_mesusu'))."','$this->rh03_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh03_padrao"]) || $this->rh03_padrao != "")
             $resac = db_query("insert into db_acount values($acount,1159,7040,'".AddSlashes(pg_result($resaco,$conresaco,'rh03_padrao'))."','$this->rh03_padrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh03_padraoprev"]) || $this->rh03_padraoprev != "")
             $resac = db_query("insert into db_acount values($acount,1159,20897,'".AddSlashes(pg_result($resaco,$conresaco,'rh03_padraoprev'))."','$this->rh03_padraoprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh03_regime"]) || $this->rh03_regime != "")
             $resac = db_query("insert into db_acount values($acount,1159,7642,'".AddSlashes(pg_result($resaco,$conresaco,'rh03_regime'))."','$this->rh03_regime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "padrao do funcionário nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh03_seqpes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "padrao do funcionário nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh03_seqpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh03_seqpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh03_seqpes=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh03_seqpes));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,7039,'$rh03_seqpes','E')");
           $resac  = db_query("insert into db_acount values($acount,1159,7039,'','".AddSlashes(pg_result($resaco,$iresaco,'rh03_seqpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1159,7643,'','".AddSlashes(pg_result($resaco,$iresaco,'rh03_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1159,7644,'','".AddSlashes(pg_result($resaco,$iresaco,'rh03_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1159,7040,'','".AddSlashes(pg_result($resaco,$iresaco,'rh03_padrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1159,20897,'','".AddSlashes(pg_result($resaco,$iresaco,'rh03_padraoprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1159,7642,'','".AddSlashes(pg_result($resaco,$iresaco,'rh03_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpespadrao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh03_seqpes)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh03_seqpes = $rh03_seqpes ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "padrao do funcionário nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh03_seqpes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "padrao do funcionário nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh03_seqpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh03_seqpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:rhpespadrao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh03_seqpes = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpespadrao ";
     $sql .= "      inner join padroes  on  padroes.r02_anousu = rhpespadrao.rh03_anousu 
		                                   and  padroes.r02_mesusu = rhpespadrao.rh03_mesusu 
																			 and  padroes.r02_regime = rhpespadrao.rh03_regime 
																			 and  padroes.r02_codigo = rhpespadrao.rh03_padrao 
																			 and  padroes.r02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_seqpes = rhpespadrao.rh03_seqpes";
     $sql .= "      inner join tpcontra  on  tpcontra.h13_codigo = rhpessoalmov.rh02_tpcont";
     $sql .= "      inner join rhregime  on  rhregime.rh30_codreg = rhpessoalmov.rh02_codreg";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh03_seqpes)) {
         $sql2 .= " where rhpespadrao.rh03_seqpes = $rh03_seqpes "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($rh03_seqpes = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpespadrao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh03_seqpes)){
         $sql2 .= " where rhpespadrao.rh03_seqpes = $rh03_seqpes "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

   function atualiza_incluir (){
  	 $this->incluir($this->rh03_seqpes);
   }
   function sql_query_padroes ( $rh03_seqpes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpespadrao ";
     $sql .= "      inner join padroes  on  padroes.r02_anousu = rhpespadrao.rh03_anousu 
		                                   and  padroes.r02_mesusu = rhpespadrao.rh03_mesusu 
																			 and  padroes.r02_regime = rhpespadrao.rh03_regime 
																			 and  padroes.r02_codigo = rhpespadrao.rh03_padrao
																			 and  padroes.r02_instit = ".db_getsession("DB_instit")." ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh03_seqpes!=null ){
         $sql2 .= " where rhpespadrao.rh03_seqpes = $rh03_seqpes "; 
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
  function sql_query_padrao_previdencia( $rh03_seqpes=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from rhpespadrao ";
    $sql .= "      inner join padroes  on  padroes.r02_anousu = rhpespadrao.rh03_anousu
		                                   and  padroes.r02_mesusu = rhpespadrao.rh03_mesusu
																			 and  padroes.r02_regime = rhpespadrao.rh03_regime
																			 and  padroes.r02_codigo = rhpespadrao.rh03_padraoprev
																			 and  padroes.r02_instit = ".db_getsession("DB_instit")." ";
    $sql2 = "";
    if($dbwhere==""){
      if($rh03_seqpes!=null ){
        $sql2 .= " where rhpespadrao.rh03_seqpes = $rh03_seqpes ";
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
   function sql_query_retorno ( $rh03_seqpes=null,$campos="*",$ordem=null,$dbwhere="",$anonovo,$mesnovo){ 
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
     $sql .= " from rhpespadrao ";
     $sql .= "      inner join rhpessoalmov on rh03_seqpes=rh02_seqpes ";
     $sql .= "      left  join rhpessoal on rh01_regist=rh02_regist ";
     $sql .= "      left  join rhpessoalmov a on a.rh02_regist=rh01_regist 
		                                         and a.rh02_anousu=".$anonovo."
                                             and a.rh02_mesusu=".$mesnovo."
																						 and a.rh02_instit=".db_getsession("DB_instit")." ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh03_seqpes!=null ){
         $sql2 .= " where rhpespadrao.rh03_seqpes = $rh03_seqpes "; 
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
