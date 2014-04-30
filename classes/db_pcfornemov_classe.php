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

//MODULO: compras
//CLASSE DA ENTIDADE pcfornemov
class cl_pcfornemov { 
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
   var $pc62_codmov = 0; 
   var $pc62_numcgm = 0; 
   var $pc62_dtlanc_dia = null; 
   var $pc62_dtlanc_mes = null; 
   var $pc62_dtlanc_ano = null; 
   var $pc62_dtlanc = null; 
   var $pc62_hist = null; 
   var $pc62_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc62_codmov = int4 = Movimento 
                 pc62_numcgm = int4 = Fornecedor 
                 pc62_dtlanc = date = Lançamento 
                 pc62_hist = text = Histório 
                 pc62_id_usuario = int4 = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_pcfornemov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcfornemov"); 
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
       $this->pc62_codmov = ($this->pc62_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["pc62_codmov"]:$this->pc62_codmov);
       $this->pc62_numcgm = ($this->pc62_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["pc62_numcgm"]:$this->pc62_numcgm);
       if($this->pc62_dtlanc == ""){
         $this->pc62_dtlanc_dia = ($this->pc62_dtlanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc62_dtlanc_dia"]:$this->pc62_dtlanc_dia);
         $this->pc62_dtlanc_mes = ($this->pc62_dtlanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc62_dtlanc_mes"]:$this->pc62_dtlanc_mes);
         $this->pc62_dtlanc_ano = ($this->pc62_dtlanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc62_dtlanc_ano"]:$this->pc62_dtlanc_ano);
         if($this->pc62_dtlanc_dia != ""){
            $this->pc62_dtlanc = $this->pc62_dtlanc_ano."-".$this->pc62_dtlanc_mes."-".$this->pc62_dtlanc_dia;
         }
       }
       $this->pc62_hist = ($this->pc62_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["pc62_hist"]:$this->pc62_hist);
       $this->pc62_id_usuario = ($this->pc62_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc62_id_usuario"]:$this->pc62_id_usuario);
     }else{
       $this->pc62_codmov = ($this->pc62_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["pc62_codmov"]:$this->pc62_codmov);
     }
   }
   // funcao para inclusao
   function incluir ($pc62_codmov){ 
      $this->atualizacampos();
     if($this->pc62_numcgm == null ){ 
       $this->erro_sql = " Campo Fornecedor nao Informado.";
       $this->erro_campo = "pc62_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc62_dtlanc == null ){ 
       $this->erro_sql = " Campo Lançamento nao Informado.";
       $this->erro_campo = "pc62_dtlanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc62_hist == null ){ 
       $this->erro_sql = " Campo Histório nao Informado.";
       $this->erro_campo = "pc62_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc62_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "pc62_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc62_codmov == "" || $pc62_codmov == null ){
       $result = db_query("select nextval('pcfornemov_pc62_codmov_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcfornemov_pc62_codmov_seq do campo: pc62_codmov"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc62_codmov = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcfornemov_pc62_codmov_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc62_codmov)){
         $this->erro_sql = " Campo pc62_codmov maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc62_codmov = $pc62_codmov; 
       }
     }
     if(($this->pc62_codmov == null) || ($this->pc62_codmov == "") ){ 
       $this->erro_sql = " Campo pc62_codmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcfornemov(
                                       pc62_codmov 
                                      ,pc62_numcgm 
                                      ,pc62_dtlanc 
                                      ,pc62_hist 
                                      ,pc62_id_usuario 
                       )
                values (
                                $this->pc62_codmov 
                               ,$this->pc62_numcgm 
                               ,".($this->pc62_dtlanc == "null" || $this->pc62_dtlanc == ""?"null":"'".$this->pc62_dtlanc."'")." 
                               ,'$this->pc62_hist' 
                               ,$this->pc62_id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentação do Fornmecedor ($this->pc62_codmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentação do Fornmecedor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentação do Fornmecedor ($this->pc62_codmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc62_codmov;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc62_codmov));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5997,'$this->pc62_codmov','I')");
       $resac = db_query("insert into db_acount values($acount,962,5997,'','".AddSlashes(pg_result($resaco,0,'pc62_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,962,5998,'','".AddSlashes(pg_result($resaco,0,'pc62_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,962,5999,'','".AddSlashes(pg_result($resaco,0,'pc62_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,962,6000,'','".AddSlashes(pg_result($resaco,0,'pc62_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,962,6002,'','".AddSlashes(pg_result($resaco,0,'pc62_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc62_codmov=null) { 
      $this->atualizacampos();
     $sql = " update pcfornemov set ";
     $virgula = "";
     if(trim($this->pc62_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc62_codmov"])){ 
       $sql  .= $virgula." pc62_codmov = $this->pc62_codmov ";
       $virgula = ",";
       if(trim($this->pc62_codmov) == null ){ 
         $this->erro_sql = " Campo Movimento nao Informado.";
         $this->erro_campo = "pc62_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc62_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc62_numcgm"])){ 
       $sql  .= $virgula." pc62_numcgm = $this->pc62_numcgm ";
       $virgula = ",";
       if(trim($this->pc62_numcgm) == null ){ 
         $this->erro_sql = " Campo Fornecedor nao Informado.";
         $this->erro_campo = "pc62_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc62_dtlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc62_dtlanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc62_dtlanc_dia"] !="") ){ 
       $sql  .= $virgula." pc62_dtlanc = '$this->pc62_dtlanc' ";
       $virgula = ",";
       if(trim($this->pc62_dtlanc) == null ){ 
         $this->erro_sql = " Campo Lançamento nao Informado.";
         $this->erro_campo = "pc62_dtlanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc62_dtlanc_dia"])){ 
         $sql  .= $virgula." pc62_dtlanc = null ";
         $virgula = ",";
         if(trim($this->pc62_dtlanc) == null ){ 
           $this->erro_sql = " Campo Lançamento nao Informado.";
           $this->erro_campo = "pc62_dtlanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc62_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc62_hist"])){ 
       $sql  .= $virgula." pc62_hist = '$this->pc62_hist' ";
       $virgula = ",";
       if(trim($this->pc62_hist) == null ){ 
         $this->erro_sql = " Campo Histório nao Informado.";
         $this->erro_campo = "pc62_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc62_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc62_id_usuario"])){ 
       $sql  .= $virgula." pc62_id_usuario = $this->pc62_id_usuario ";
       $virgula = ",";
       if(trim($this->pc62_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "pc62_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc62_codmov!=null){
       $sql .= " pc62_codmov = $this->pc62_codmov";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc62_codmov));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5997,'$this->pc62_codmov','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc62_codmov"]))
           $resac = db_query("insert into db_acount values($acount,962,5997,'".AddSlashes(pg_result($resaco,$conresaco,'pc62_codmov'))."','$this->pc62_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc62_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,962,5998,'".AddSlashes(pg_result($resaco,$conresaco,'pc62_numcgm'))."','$this->pc62_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc62_dtlanc"]))
           $resac = db_query("insert into db_acount values($acount,962,5999,'".AddSlashes(pg_result($resaco,$conresaco,'pc62_dtlanc'))."','$this->pc62_dtlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc62_hist"]))
           $resac = db_query("insert into db_acount values($acount,962,6000,'".AddSlashes(pg_result($resaco,$conresaco,'pc62_hist'))."','$this->pc62_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc62_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,962,6002,'".AddSlashes(pg_result($resaco,$conresaco,'pc62_id_usuario'))."','$this->pc62_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação do Fornmecedor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc62_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação do Fornmecedor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc62_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc62_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc62_codmov=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc62_codmov));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5997,'$pc62_codmov','E')");
         $resac = db_query("insert into db_acount values($acount,962,5997,'','".AddSlashes(pg_result($resaco,$iresaco,'pc62_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,962,5998,'','".AddSlashes(pg_result($resaco,$iresaco,'pc62_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,962,5999,'','".AddSlashes(pg_result($resaco,$iresaco,'pc62_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,962,6000,'','".AddSlashes(pg_result($resaco,$iresaco,'pc62_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,962,6002,'','".AddSlashes(pg_result($resaco,$iresaco,'pc62_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcfornemov
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc62_codmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc62_codmov = $pc62_codmov ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação do Fornmecedor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc62_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação do Fornmecedor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc62_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc62_codmov;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcfornemov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc62_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornemov ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcfornemov.pc62_id_usuario";
     $sql .= "      inner join pcforne  on  pcforne.pc60_numcgm = pcfornemov.pc62_numcgm";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcforne.pc60_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($pc62_codmov!=null ){
         $sql2 .= " where pcfornemov.pc62_codmov = $pc62_codmov "; 
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
   function sql_query_file ( $pc62_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornemov ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc62_codmov!=null ){
         $sql2 .= " where pcfornemov.pc62_codmov = $pc62_codmov "; 
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