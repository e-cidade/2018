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
//CLASSE DA ENTIDADE pensaoretencao
class cl_pensaoretencao { 
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
   var $rh77_sequencial = 0; 
   var $rh77_numcgm = 0; 
   var $rh77_regist = 0; 
   var $rh77_anousu = 0; 
   var $rh77_mesusu = 0; 
   var $rh77_retencaotiporec = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh77_sequencial = int4 = Sequencial 
                 rh77_numcgm = int4 = Numcgm 
                 rh77_regist = int4 = Matrícula 
                 rh77_anousu = int4 = Ano 
                 rh77_mesusu = int4 = Mês 
                 rh77_retencaotiporec = int4 = Retenção 
                 ";
   //funcao construtor da classe 
   function cl_pensaoretencao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pensaoretencao"); 
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
       $this->rh77_sequencial = ($this->rh77_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh77_sequencial"]:$this->rh77_sequencial);
       $this->rh77_numcgm = ($this->rh77_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["rh77_numcgm"]:$this->rh77_numcgm);
       $this->rh77_regist = ($this->rh77_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh77_regist"]:$this->rh77_regist);
       $this->rh77_anousu = ($this->rh77_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh77_anousu"]:$this->rh77_anousu);
       $this->rh77_mesusu = ($this->rh77_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh77_mesusu"]:$this->rh77_mesusu);
       $this->rh77_retencaotiporec = ($this->rh77_retencaotiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["rh77_retencaotiporec"]:$this->rh77_retencaotiporec);
     }else{
       $this->rh77_sequencial = ($this->rh77_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh77_sequencial"]:$this->rh77_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh77_sequencial){ 
      $this->atualizacampos();
     if($this->rh77_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "rh77_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh77_regist == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "rh77_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh77_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "rh77_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh77_mesusu == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "rh77_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh77_retencaotiporec == null ){ 
       $this->erro_sql = " Campo Retenção nao Informado.";
       $this->erro_campo = "rh77_retencaotiporec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh77_sequencial == "" || $rh77_sequencial == null ){
       $result = db_query("select nextval('pensaoretencao_rh77_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pensaoretencao_rh77_sequencial_seq do campo: rh77_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh77_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pensaoretencao_rh77_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh77_sequencial)){
         $this->erro_sql = " Campo rh77_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh77_sequencial = $rh77_sequencial; 
       }
     }
     if(($this->rh77_sequencial == null) || ($this->rh77_sequencial == "") ){ 
       $this->erro_sql = " Campo rh77_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pensaoretencao(
                                       rh77_sequencial 
                                      ,rh77_numcgm 
                                      ,rh77_regist 
                                      ,rh77_anousu 
                                      ,rh77_mesusu 
                                      ,rh77_retencaotiporec 
                       )
                values (
                                $this->rh77_sequencial 
                               ,$this->rh77_numcgm 
                               ,$this->rh77_regist 
                               ,$this->rh77_anousu 
                               ,$this->rh77_mesusu 
                               ,$this->rh77_retencaotiporec 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pensaoretencao ($this->rh77_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pensaoretencao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pensaoretencao ($this->rh77_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh77_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh77_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14403,'$this->rh77_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2539,14403,'','".AddSlashes(pg_result($resaco,0,'rh77_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2539,14404,'','".AddSlashes(pg_result($resaco,0,'rh77_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2539,14405,'','".AddSlashes(pg_result($resaco,0,'rh77_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2539,14406,'','".AddSlashes(pg_result($resaco,0,'rh77_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2539,14407,'','".AddSlashes(pg_result($resaco,0,'rh77_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2539,14408,'','".AddSlashes(pg_result($resaco,0,'rh77_retencaotiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh77_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pensaoretencao set ";
     $virgula = "";
     if(trim($this->rh77_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh77_sequencial"])){ 
       $sql  .= $virgula." rh77_sequencial = $this->rh77_sequencial ";
       $virgula = ",";
       if(trim($this->rh77_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh77_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh77_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh77_numcgm"])){ 
       $sql  .= $virgula." rh77_numcgm = $this->rh77_numcgm ";
       $virgula = ",";
       if(trim($this->rh77_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "rh77_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh77_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh77_regist"])){ 
       $sql  .= $virgula." rh77_regist = $this->rh77_regist ";
       $virgula = ",";
       if(trim($this->rh77_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "rh77_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh77_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh77_anousu"])){ 
       $sql  .= $virgula." rh77_anousu = $this->rh77_anousu ";
       $virgula = ",";
       if(trim($this->rh77_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "rh77_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh77_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh77_mesusu"])){ 
       $sql  .= $virgula." rh77_mesusu = $this->rh77_mesusu ";
       $virgula = ",";
       if(trim($this->rh77_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "rh77_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh77_retencaotiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh77_retencaotiporec"])){ 
       $sql  .= $virgula." rh77_retencaotiporec = $this->rh77_retencaotiporec ";
       $virgula = ",";
       if(trim($this->rh77_retencaotiporec) == null ){ 
         $this->erro_sql = " Campo Retenção nao Informado.";
         $this->erro_campo = "rh77_retencaotiporec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh77_sequencial!=null){
       $sql .= " rh77_sequencial = $this->rh77_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh77_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14403,'$this->rh77_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh77_sequencial"]) || $this->rh77_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2539,14403,'".AddSlashes(pg_result($resaco,$conresaco,'rh77_sequencial'))."','$this->rh77_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh77_numcgm"]) || $this->rh77_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,2539,14404,'".AddSlashes(pg_result($resaco,$conresaco,'rh77_numcgm'))."','$this->rh77_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh77_regist"]) || $this->rh77_regist != "")
           $resac = db_query("insert into db_acount values($acount,2539,14405,'".AddSlashes(pg_result($resaco,$conresaco,'rh77_regist'))."','$this->rh77_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh77_anousu"]) || $this->rh77_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2539,14406,'".AddSlashes(pg_result($resaco,$conresaco,'rh77_anousu'))."','$this->rh77_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh77_mesusu"]) || $this->rh77_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,2539,14407,'".AddSlashes(pg_result($resaco,$conresaco,'rh77_mesusu'))."','$this->rh77_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh77_retencaotiporec"]) || $this->rh77_retencaotiporec != "")
           $resac = db_query("insert into db_acount values($acount,2539,14408,'".AddSlashes(pg_result($resaco,$conresaco,'rh77_retencaotiporec'))."','$this->rh77_retencaotiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pensaoretencao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh77_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pensaoretencao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh77_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh77_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh77_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh77_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14403,'$rh77_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2539,14403,'','".AddSlashes(pg_result($resaco,$iresaco,'rh77_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2539,14404,'','".AddSlashes(pg_result($resaco,$iresaco,'rh77_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2539,14405,'','".AddSlashes(pg_result($resaco,$iresaco,'rh77_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2539,14406,'','".AddSlashes(pg_result($resaco,$iresaco,'rh77_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2539,14407,'','".AddSlashes(pg_result($resaco,$iresaco,'rh77_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2539,14408,'','".AddSlashes(pg_result($resaco,$iresaco,'rh77_retencaotiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pensaoretencao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh77_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh77_sequencial = $rh77_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pensaoretencao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh77_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pensaoretencao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh77_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh77_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pensaoretencao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh77_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pensaoretencao ";
     $sql .= "      inner join pensao  on  pensao.r52_anousu = pensaoretencao.rh77_anousu and  pensao.r52_mesusu = pensaoretencao.rh77_mesusu and  pensao.r52_regist = pensaoretencao.rh77_regist and  pensao.r52_numcgm = pensaoretencao.rh77_numcgm";
     $sql .= "      inner join retencaotiporec  on  retencaotiporec.e21_sequencial = pensaoretencao.rh77_retencaotiporec";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pensao.r52_numcgm";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = pensao.r52_anousu and  pessoal.r01_mesusu = pensao.r52_mesusu and  pessoal.r01_regist = pensao.r52_regist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = retencaotiporec.e21_receita";
     $sql .= "      inner join db_config  on  db_config.codigo = retencaotiporec.e21_instit";
     $sql .= "      inner join retencaotipocalc  on  retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc";
     $sql .= "      inner join retencaotiporecgrupo  on  retencaotiporecgrupo.e01_sequencial = retencaotiporec.e21_retencaotiporecgrupo";
     $sql2 = "";
     if($dbwhere==""){
       if($rh77_sequencial!=null ){
         $sql2 .= " where pensaoretencao.rh77_sequencial = $rh77_sequencial "; 
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
   function sql_query_file ( $rh77_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pensaoretencao ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh77_sequencial!=null ){
         $sql2 .= " where pensaoretencao.rh77_sequencial = $rh77_sequencial "; 
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
  
   function sql_query_dados( $rh77_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pensaoretencao ";
     $sql .= "      inner join pensao               on pensao.r52_anousu                   = pensaoretencao.rh77_anousu 
                                                   and pensao.r52_mesusu                   = pensaoretencao.rh77_mesusu 
                                                   and pensao.r52_regist                   = pensaoretencao.rh77_regist 
                                                   and pensao.r52_numcgm                   = pensaoretencao.rh77_numcgm";
     $sql .= "      inner join retencaotiporec      on retencaotiporec.e21_sequencial      = pensaoretencao.rh77_retencaotiporec";
     $sql .= "      inner join cgm                  on cgm.z01_numcgm                      = pensao.r52_numcgm";
     $sql .= "      inner join tabrec               on tabrec.k02_codigo                   = retencaotiporec.e21_receita";
     $sql .= "      inner join db_config            on db_config.codigo                    = retencaotiporec.e21_instit";
     $sql .= "      inner join retencaotipocalc     on retencaotipocalc.e32_sequencial     = retencaotiporec.e21_retencaotipocalc";
     $sql .= "      inner join retencaotiporecgrupo on retencaotiporecgrupo.e01_sequencial = retencaotiporec.e21_retencaotiporecgrupo";
     $sql2 = "";
     if($dbwhere==""){
       if($rh77_sequencial!=null ){
         $sql2 .= " where pensaoretencao.rh77_sequencial = $rh77_sequencial "; 
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
  
  function sql_query_retorno ( $rh77_sequencial=null,$campos="*",$ordem=null,$dbwhere="") { 
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
     $sql .= " from pensaoretencao ";
     $sql .= "      inner join pensao  on  pensao.r52_anousu = pensaoretencao.rh77_anousu ";
     $sql .= "                        and  pensao.r52_mesusu = pensaoretencao.rh77_mesusu ";
     $sql .= "                        and  pensao.r52_regist = pensaoretencao.rh77_regist ";
     $sql .= "                        and  pensao.r52_numcgm = pensaoretencao.rh77_numcgm ";
     $sql .= "      inner join rhpessoalmov  on  pensao.r52_anousu = rh02_anousu "; 
     $sql .= "                              and  pensao.r52_mesusu = rh02_mesusu ";
     $sql .= "                              and  pensao.r52_regist = rh02_regist ";
     $sql .= "      inner join retencaotiporec  on  retencaotiporec.e21_sequencial = pensaoretencao.rh77_retencaotiporec";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pensao.r52_numcgm";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = pensao.r52_regist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = retencaotiporec.e21_receita";
     $sql .= "      inner join db_config  on  db_config.codigo = retencaotiporec.e21_instit";
     $sql .= "      inner join retencaotipocalc  on  retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc";
     $sql .= "      inner join retencaotiporecgrupo  on  retencaotiporecgrupo.e01_sequencial = retencaotiporec.e21_retencaotiporecgrupo";
     $sql2 = "";
     if($dbwhere==""){
       if($rh77_sequencial!=null ){
         $sql2 .= " where pensaoretencao.rh77_sequencial = $rh77_sequencial "; 
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