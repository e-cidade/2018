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
//CLASSE DA ENTIDADE funcao
class cl_funcao { 
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
   var $r37_anousu = 0; 
   var $r37_mesusu = 0; 
   var $r37_funcao = 0; 
   var $r37_descr = null; 
   var $r37_vagas = 0; 
   var $r37_cbo = null; 
   var $r37_lei = null; 
   var $r37_class = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r37_anousu = int4 = Ano 
                 r37_mesusu = int4 = Mês 
                 r37_funcao = int4 = Cargo 
                 r37_descr = varchar(30) = Descrição 
                 r37_vagas = int4 = Vagas 
                 r37_cbo = varchar(6) = CBO 
                 r37_lei = varchar(10) = Lei 
                 r37_class = varchar(5) = Classificação 
                 ";
   //funcao construtor da classe 
   function cl_funcao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("funcao"); 
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
       $this->r37_anousu = ($this->r37_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r37_anousu"]:$this->r37_anousu);
       $this->r37_mesusu = ($this->r37_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r37_mesusu"]:$this->r37_mesusu);
       $this->r37_funcao = ($this->r37_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["r37_funcao"]:$this->r37_funcao);
       $this->r37_descr = ($this->r37_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r37_descr"]:$this->r37_descr);
       $this->r37_vagas = ($this->r37_vagas == ""?@$GLOBALS["HTTP_POST_VARS"]["r37_vagas"]:$this->r37_vagas);
       $this->r37_cbo = ($this->r37_cbo == ""?@$GLOBALS["HTTP_POST_VARS"]["r37_cbo"]:$this->r37_cbo);
       $this->r37_lei = ($this->r37_lei == ""?@$GLOBALS["HTTP_POST_VARS"]["r37_lei"]:$this->r37_lei);
       $this->r37_class = ($this->r37_class == ""?@$GLOBALS["HTTP_POST_VARS"]["r37_class"]:$this->r37_class);
     }else{
       $this->r37_anousu = ($this->r37_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r37_anousu"]:$this->r37_anousu);
       $this->r37_mesusu = ($this->r37_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r37_mesusu"]:$this->r37_mesusu);
       $this->r37_funcao = ($this->r37_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["r37_funcao"]:$this->r37_funcao);
     }
   }
   // funcao para inclusao
   function incluir ($r37_anousu,$r37_mesusu,$r37_funcao){ 
      $this->atualizacampos();
     if($this->r37_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "r37_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r37_vagas == null ){ 
       $this->r37_vagas = "0";
     }
     if($this->r37_lei == null ){ 
       $this->r37_lei = "0";
     }
       $this->r37_anousu = $r37_anousu; 
       $this->r37_mesusu = $r37_mesusu; 
       $this->r37_funcao = $r37_funcao; 
     if(($this->r37_anousu == null) || ($this->r37_anousu == "") ){ 
       $this->erro_sql = " Campo r37_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r37_mesusu == null) || ($this->r37_mesusu == "") ){ 
       $this->erro_sql = " Campo r37_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r37_funcao == null) || ($this->r37_funcao == "") ){ 
       $this->erro_sql = " Campo r37_funcao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into funcao(
                                       r37_anousu 
                                      ,r37_mesusu 
                                      ,r37_funcao 
                                      ,r37_descr 
                                      ,r37_vagas 
                                      ,r37_cbo 
                                      ,r37_lei 
                                      ,r37_class 
                       )
                values (
                                $this->r37_anousu 
                               ,$this->r37_mesusu 
                               ,$this->r37_funcao 
                               ,'$this->r37_descr' 
                               ,$this->r37_vagas 
                               ,'$this->r37_cbo' 
                               ,'$this->r37_lei' 
                               ,'$this->r37_class' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de funcoes                                ($this->r37_anousu."-".$this->r37_mesusu."-".$this->r37_funcao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de funcoes                                já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de funcoes                                ($this->r37_anousu."-".$this->r37_mesusu."-".$this->r37_funcao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r37_anousu."-".$this->r37_mesusu."-".$this->r37_funcao;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r37_anousu,$this->r37_mesusu,$this->r37_funcao));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3935,'$this->r37_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3936,'$this->r37_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3937,'$this->r37_funcao','I')");
       $resac = db_query("insert into db_acount values($acount,552,3935,'','".AddSlashes(pg_result($resaco,0,'r37_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,552,3936,'','".AddSlashes(pg_result($resaco,0,'r37_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,552,3937,'','".AddSlashes(pg_result($resaco,0,'r37_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,552,3938,'','".AddSlashes(pg_result($resaco,0,'r37_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,552,3939,'','".AddSlashes(pg_result($resaco,0,'r37_vagas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,552,3940,'','".AddSlashes(pg_result($resaco,0,'r37_cbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,552,4595,'','".AddSlashes(pg_result($resaco,0,'r37_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,552,4596,'','".AddSlashes(pg_result($resaco,0,'r37_class'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r37_anousu=null,$r37_mesusu=null,$r37_funcao=null) { 
      $this->atualizacampos();
     $sql = " update funcao set ";
     $virgula = "";
     if(trim($this->r37_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r37_anousu"])){ 
       $sql  .= $virgula." r37_anousu = $this->r37_anousu ";
       $virgula = ",";
       if(trim($this->r37_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "r37_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r37_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r37_mesusu"])){ 
       $sql  .= $virgula." r37_mesusu = $this->r37_mesusu ";
       $virgula = ",";
       if(trim($this->r37_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "r37_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r37_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r37_funcao"])){ 
       $sql  .= $virgula." r37_funcao = $this->r37_funcao ";
       $virgula = ",";
       if(trim($this->r37_funcao) == null ){ 
         $this->erro_sql = " Campo Cargo nao Informado.";
         $this->erro_campo = "r37_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r37_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r37_descr"])){ 
       $sql  .= $virgula." r37_descr = '$this->r37_descr' ";
       $virgula = ",";
       if(trim($this->r37_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "r37_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r37_vagas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r37_vagas"])){ 
        if(trim($this->r37_vagas)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r37_vagas"])){ 
           $this->r37_vagas = "0" ; 
        } 
       $sql  .= $virgula." r37_vagas = $this->r37_vagas ";
       $virgula = ",";
     }
     if(trim($this->r37_cbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r37_cbo"])){ 
       $sql  .= $virgula." r37_cbo = '$this->r37_cbo' ";
       $virgula = ",";
     }
     if(trim($this->r37_lei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r37_lei"])){ 
       $sql  .= $virgula." r37_lei = '$this->r37_lei' ";
       $virgula = ",";
     }
     if(trim($this->r37_class)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r37_class"])){ 
       $sql  .= $virgula." r37_class = '$this->r37_class' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($r37_anousu!=null){
       $sql .= " r37_anousu = $this->r37_anousu";
     }
     if($r37_mesusu!=null){
       $sql .= " and  r37_mesusu = $this->r37_mesusu";
     }
     if($r37_funcao!=null){
       $sql .= " and  r37_funcao = $this->r37_funcao";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r37_anousu,$this->r37_mesusu,$this->r37_funcao));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3935,'$this->r37_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3936,'$this->r37_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3937,'$this->r37_funcao','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r37_anousu"]))
           $resac = db_query("insert into db_acount values($acount,552,3935,'".AddSlashes(pg_result($resaco,$conresaco,'r37_anousu'))."','$this->r37_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r37_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,552,3936,'".AddSlashes(pg_result($resaco,$conresaco,'r37_mesusu'))."','$this->r37_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r37_funcao"]))
           $resac = db_query("insert into db_acount values($acount,552,3937,'".AddSlashes(pg_result($resaco,$conresaco,'r37_funcao'))."','$this->r37_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r37_descr"]))
           $resac = db_query("insert into db_acount values($acount,552,3938,'".AddSlashes(pg_result($resaco,$conresaco,'r37_descr'))."','$this->r37_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r37_vagas"]))
           $resac = db_query("insert into db_acount values($acount,552,3939,'".AddSlashes(pg_result($resaco,$conresaco,'r37_vagas'))."','$this->r37_vagas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r37_cbo"]))
           $resac = db_query("insert into db_acount values($acount,552,3940,'".AddSlashes(pg_result($resaco,$conresaco,'r37_cbo'))."','$this->r37_cbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r37_lei"]))
           $resac = db_query("insert into db_acount values($acount,552,4595,'".AddSlashes(pg_result($resaco,$conresaco,'r37_lei'))."','$this->r37_lei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r37_class"]))
           $resac = db_query("insert into db_acount values($acount,552,4596,'".AddSlashes(pg_result($resaco,$conresaco,'r37_class'))."','$this->r37_class',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de funcoes                                nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r37_anousu."-".$this->r37_mesusu."-".$this->r37_funcao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de funcoes                                nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r37_anousu."-".$this->r37_mesusu."-".$this->r37_funcao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r37_anousu."-".$this->r37_mesusu."-".$this->r37_funcao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r37_anousu=null,$r37_mesusu=null,$r37_funcao=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r37_anousu,$r37_mesusu,$r37_funcao));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3935,'$r37_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3936,'$r37_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3937,'$r37_funcao','E')");
         $resac = db_query("insert into db_acount values($acount,552,3935,'','".AddSlashes(pg_result($resaco,$iresaco,'r37_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,552,3936,'','".AddSlashes(pg_result($resaco,$iresaco,'r37_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,552,3937,'','".AddSlashes(pg_result($resaco,$iresaco,'r37_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,552,3938,'','".AddSlashes(pg_result($resaco,$iresaco,'r37_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,552,3939,'','".AddSlashes(pg_result($resaco,$iresaco,'r37_vagas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,552,3940,'','".AddSlashes(pg_result($resaco,$iresaco,'r37_cbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,552,4595,'','".AddSlashes(pg_result($resaco,$iresaco,'r37_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,552,4596,'','".AddSlashes(pg_result($resaco,$iresaco,'r37_class'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from funcao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r37_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r37_anousu = $r37_anousu ";
        }
        if($r37_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r37_mesusu = $r37_mesusu ";
        }
        if($r37_funcao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r37_funcao = $r37_funcao ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de funcoes                                nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r37_anousu."-".$r37_mesusu."-".$r37_funcao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de funcoes                                nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r37_anousu."-".$r37_mesusu."-".$r37_funcao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r37_anousu."-".$r37_mesusu."-".$r37_funcao;
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
        $this->erro_sql   = "Record Vazio na Tabela:funcao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r37_anousu=null,$r37_mesusu=null,$r37_funcao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from funcao ";
     $sql2 = "";
     if($dbwhere==""){
       if($r37_anousu!=null ){
         $sql2 .= " where funcao.r37_anousu = $r37_anousu "; 
       } 
       if($r37_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " funcao.r37_mesusu = $r37_mesusu "; 
       } 
       if($r37_funcao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " funcao.r37_funcao = $r37_funcao "; 
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
   function sql_query_file ( $r37_anousu=null,$r37_mesusu=null,$r37_funcao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from funcao ";
     $sql2 = "";
     if($dbwhere==""){
       if($r37_anousu!=null ){
         $sql2 .= " where funcao.r37_anousu = $r37_anousu "; 
       } 
       if($r37_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " funcao.r37_mesusu = $r37_mesusu "; 
       } 
       if($r37_funcao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " funcao.r37_funcao = $r37_funcao "; 
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