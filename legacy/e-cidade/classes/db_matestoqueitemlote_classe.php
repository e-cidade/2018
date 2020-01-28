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

//MODULO: material
//CLASSE DA ENTIDADE matestoqueitemlote
class cl_matestoqueitemlote { 
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
   var $m77_sequencial = 0; 
   var $m77_lote = null; 
   var $m77_dtvalidade_dia = null; 
   var $m77_dtvalidade_mes = null; 
   var $m77_dtvalidade_ano = null; 
   var $m77_dtvalidade = null; 
   var $m77_matestoqueitem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m77_sequencial = int4 = Código Sequencial 
                 m77_lote = varchar(50) = Lote do Material 
                 m77_dtvalidade = date = Data de Validade 
                 m77_matestoqueitem = int4 = Código do Item 
                 ";
   //funcao construtor da classe 
   function cl_matestoqueitemlote() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueitemlote"); 
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
       $this->m77_sequencial = ($this->m77_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m77_sequencial"]:$this->m77_sequencial);
       $this->m77_lote = ($this->m77_lote == ""?@$GLOBALS["HTTP_POST_VARS"]["m77_lote"]:$this->m77_lote);
       if($this->m77_dtvalidade == ""){
         $this->m77_dtvalidade_dia = ($this->m77_dtvalidade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m77_dtvalidade_dia"]:$this->m77_dtvalidade_dia);
         $this->m77_dtvalidade_mes = ($this->m77_dtvalidade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m77_dtvalidade_mes"]:$this->m77_dtvalidade_mes);
         $this->m77_dtvalidade_ano = ($this->m77_dtvalidade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m77_dtvalidade_ano"]:$this->m77_dtvalidade_ano);
         if($this->m77_dtvalidade_dia != ""){
            $this->m77_dtvalidade = $this->m77_dtvalidade_ano."-".$this->m77_dtvalidade_mes."-".$this->m77_dtvalidade_dia;
         }
       }
       $this->m77_matestoqueitem = ($this->m77_matestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m77_matestoqueitem"]:$this->m77_matestoqueitem);
     }else{
       $this->m77_sequencial = ($this->m77_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m77_sequencial"]:$this->m77_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m77_sequencial){ 
      $this->atualizacampos();
     if($this->m77_lote == null ){ 
       $this->erro_sql = " Campo Lote do Material nao Informado.";
       $this->erro_campo = "m77_lote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m77_dtvalidade == null ){ 
       $this->erro_sql = " Campo Data de Validade nao Informado.";
       $this->erro_campo = "m77_dtvalidade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m77_matestoqueitem == null ){ 
       $this->erro_sql = " Campo Código do Item nao Informado.";
       $this->erro_campo = "m77_matestoqueitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m77_sequencial == "" || $m77_sequencial == null ){
       $result = db_query("select nextval('matestoqueitemlote_m77_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoqueitemlote_m77_sequencial_seq do campo: m77_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m77_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matestoqueitemlote_m77_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m77_sequencial)){
         $this->erro_sql = " Campo m77_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m77_sequencial = $m77_sequencial; 
       }
     }
     if(($this->m77_sequencial == null) || ($this->m77_sequencial == "") ){ 
       $this->erro_sql = " Campo m77_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueitemlote(
                                       m77_sequencial 
                                      ,m77_lote 
                                      ,m77_dtvalidade 
                                      ,m77_matestoqueitem 
                       )
                values (
                                $this->m77_sequencial 
                               ,'$this->m77_lote' 
                               ,".($this->m77_dtvalidade == "null" || $this->m77_dtvalidade == ""?"null":"'".$this->m77_dtvalidade."'")." 
                               ,$this->m77_matestoqueitem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lote do material ($this->m77_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lote do material já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lote do material ($this->m77_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m77_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m77_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11980,'$this->m77_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2072,11980,'','".AddSlashes(pg_result($resaco,0,'m77_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2072,11981,'','".AddSlashes(pg_result($resaco,0,'m77_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2072,11982,'','".AddSlashes(pg_result($resaco,0,'m77_dtvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2072,11983,'','".AddSlashes(pg_result($resaco,0,'m77_matestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m77_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matestoqueitemlote set ";
     $virgula = "";
     if(trim($this->m77_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m77_sequencial"])){ 
       $sql  .= $virgula." m77_sequencial = $this->m77_sequencial ";
       $virgula = ",";
       if(trim($this->m77_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "m77_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m77_lote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m77_lote"])){ 
       $sql  .= $virgula." m77_lote = '$this->m77_lote' ";
       $virgula = ",";
       if(trim($this->m77_lote) == null ){ 
         $this->erro_sql = " Campo Lote do Material nao Informado.";
         $this->erro_campo = "m77_lote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m77_dtvalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m77_dtvalidade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m77_dtvalidade_dia"] !="") ){ 
       $sql  .= $virgula." m77_dtvalidade = '$this->m77_dtvalidade' ";
       $virgula = ",";
       if(trim($this->m77_dtvalidade) == null ){ 
         $this->erro_sql = " Campo Data de Validade nao Informado.";
         $this->erro_campo = "m77_dtvalidade_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m77_dtvalidade_dia"])){ 
         $sql  .= $virgula." m77_dtvalidade = null ";
         $virgula = ",";
         if(trim($this->m77_dtvalidade) == null ){ 
           $this->erro_sql = " Campo Data de Validade nao Informado.";
           $this->erro_campo = "m77_dtvalidade_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m77_matestoqueitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m77_matestoqueitem"])){ 
       $sql  .= $virgula." m77_matestoqueitem = $this->m77_matestoqueitem ";
       $virgula = ",";
       if(trim($this->m77_matestoqueitem) == null ){ 
         $this->erro_sql = " Campo Código do Item nao Informado.";
         $this->erro_campo = "m77_matestoqueitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m77_sequencial!=null){
       $sql .= " m77_sequencial = $this->m77_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m77_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11980,'$this->m77_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m77_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2072,11980,'".AddSlashes(pg_result($resaco,$conresaco,'m77_sequencial'))."','$this->m77_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m77_lote"]))
           $resac = db_query("insert into db_acount values($acount,2072,11981,'".AddSlashes(pg_result($resaco,$conresaco,'m77_lote'))."','$this->m77_lote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m77_dtvalidade"]))
           $resac = db_query("insert into db_acount values($acount,2072,11982,'".AddSlashes(pg_result($resaco,$conresaco,'m77_dtvalidade'))."','$this->m77_dtvalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m77_matestoqueitem"]))
           $resac = db_query("insert into db_acount values($acount,2072,11983,'".AddSlashes(pg_result($resaco,$conresaco,'m77_matestoqueitem'))."','$this->m77_matestoqueitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lote do material nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m77_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lote do material nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m77_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m77_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m77_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m77_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11980,'$m77_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2072,11980,'','".AddSlashes(pg_result($resaco,$iresaco,'m77_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2072,11981,'','".AddSlashes(pg_result($resaco,$iresaco,'m77_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2072,11982,'','".AddSlashes(pg_result($resaco,$iresaco,'m77_dtvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2072,11983,'','".AddSlashes(pg_result($resaco,$iresaco,'m77_matestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueitemlote
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m77_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m77_sequencial = $m77_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lote do material nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m77_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lote do material nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m77_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m77_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueitemlote";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m77_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemlote ";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemlote.m77_matestoqueitem";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql2 = "";
     if($dbwhere==""){
       if($m77_sequencial!=null ){
         $sql2 .= " where matestoqueitemlote.m77_sequencial = $m77_sequencial "; 
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
   function sql_query_file ( $m77_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemlote ";
     $sql2 = "";
     if($dbwhere==""){
       if($m77_sequencial!=null ){
         $sql2 .= " where matestoqueitemlote.m77_sequencial = $m77_sequencial "; 
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
  
  function sql_query_informacoes_lote ( $m77_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from matestoqueitemlote ";
    $sql .= "        inner join matestoqueitem   on matestoqueitemlote.m77_matestoqueitem = matestoqueitem.m71_codlanc";
    $sql .= "        inner join matestoqueinimei on matestoqueinimei.m82_matestoqueitem = matestoqueitem.m71_codlanc";
    $sql .= "        inner join matestoqueini    on matestoqueinimei.m82_matestoqueini  = matestoqueini.m80_codigo";
    $sql .= "        inner join matestoquetipo   on matestoqueini.m80_Codtipo        = matestoquetipo.m81_codtipo";
    $sql .= "        inner join matestoque       on matestoque.m70_codigo         = matestoqueitem.m71_codmatestoque";
    $sql .= "        inner join db_depart        on matestoque.m70_coddepto       = db_depart.coddepto";
    $sql2 = "";
    if($dbwhere==""){
      if($m77_sequencial!=null ){
        $sql2 .= " where matestoqueitemlote.m77_sequencial = $m77_sequencial "; 
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