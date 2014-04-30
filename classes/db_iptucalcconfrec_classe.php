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

//MODULO: Cadastro
//CLASSE DA ENTIDADE iptucalcconfrec
class cl_iptucalcconfrec { 
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
   var $j23_sequencial = 0; 
   var $j23_matric = 0; 
   var $j23_anousu = 0; 
   var $j23_recorg = 0; 
   var $j23_recdst = 0; 
   var $j23_tipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j23_sequencial = int4 = Codigo sequencial 
                 j23_matric = int4 = Matricula 
                 j23_anousu = int4 = Exercicio 
                 j23_recorg = int4 = Receita Origem 
                 j23_recdst = int4 = Receita Destino 
                 j23_tipo = int4 = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_iptucalcconfrec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptucalcconfrec"); 
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
       $this->j23_sequencial = ($this->j23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_sequencial"]:$this->j23_sequencial);
       $this->j23_matric = ($this->j23_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_matric"]:$this->j23_matric);
       $this->j23_anousu = ($this->j23_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_anousu"]:$this->j23_anousu);
       $this->j23_recorg = ($this->j23_recorg == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_recorg"]:$this->j23_recorg);
       $this->j23_recdst = ($this->j23_recdst == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_recdst"]:$this->j23_recdst);
       $this->j23_tipo = ($this->j23_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_tipo"]:$this->j23_tipo);
     }else{
       $this->j23_sequencial = ($this->j23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_sequencial"]:$this->j23_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j23_sequencial){ 
      $this->atualizacampos();
     if($this->j23_matric == null ){ 
       $this->erro_sql = " Campo Matricula nao Informado.";
       $this->erro_campo = "j23_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_anousu == null ){ 
       $this->erro_sql = " Campo Exercicio nao Informado.";
       $this->erro_campo = "j23_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_recorg == null ){ 
       $this->erro_sql = " Campo Receita Origem nao Informado.";
       $this->erro_campo = "j23_recorg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_recdst == null ){ 
       $this->erro_sql = " Campo Receita Destino nao Informado.";
       $this->erro_campo = "j23_recdst";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "j23_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j23_sequencial == "" || $j23_sequencial == null ){
       $result = db_query("select nextval('iptucalcconfrec_j23_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptucalcconfrec_j23_sequencial_seq do campo: j23_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j23_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptucalcconfrec_j23_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j23_sequencial)){
         $this->erro_sql = " Campo j23_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j23_sequencial = $j23_sequencial; 
       }
     }
     if(($this->j23_sequencial == null) || ($this->j23_sequencial == "") ){ 
       $this->erro_sql = " Campo j23_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptucalcconfrec(
                                       j23_sequencial 
                                      ,j23_matric 
                                      ,j23_anousu 
                                      ,j23_recorg 
                                      ,j23_recdst 
                                      ,j23_tipo 
                       )
                values (
                                $this->j23_sequencial 
                               ,$this->j23_matric 
                               ,$this->j23_anousu 
                               ,$this->j23_recorg 
                               ,$this->j23_recdst 
                               ,$this->j23_tipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configuracao de calculo por matricula ($this->j23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configuracao de calculo por matricula já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configuracao de calculo por matricula ($this->j23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j23_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j23_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10820,'$this->j23_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1863,10820,'','".AddSlashes(pg_result($resaco,0,'j23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1863,592,'','".AddSlashes(pg_result($resaco,0,'j23_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1863,591,'','".AddSlashes(pg_result($resaco,0,'j23_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1863,10822,'','".AddSlashes(pg_result($resaco,0,'j23_recorg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1863,10823,'','".AddSlashes(pg_result($resaco,0,'j23_recdst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1863,12595,'','".AddSlashes(pg_result($resaco,0,'j23_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j23_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update iptucalcconfrec set ";
     $virgula = "";
     if(trim($this->j23_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_sequencial"])){ 
       $sql  .= $virgula." j23_sequencial = $this->j23_sequencial ";
       $virgula = ",";
       if(trim($this->j23_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "j23_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_matric"])){ 
       $sql  .= $virgula." j23_matric = $this->j23_matric ";
       $virgula = ",";
       if(trim($this->j23_matric) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "j23_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_anousu"])){ 
       $sql  .= $virgula." j23_anousu = $this->j23_anousu ";
       $virgula = ",";
       if(trim($this->j23_anousu) == null ){ 
         $this->erro_sql = " Campo Exercicio nao Informado.";
         $this->erro_campo = "j23_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_recorg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_recorg"])){ 
       $sql  .= $virgula." j23_recorg = $this->j23_recorg ";
       $virgula = ",";
       if(trim($this->j23_recorg) == null ){ 
         $this->erro_sql = " Campo Receita Origem nao Informado.";
         $this->erro_campo = "j23_recorg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_recdst)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_recdst"])){ 
       $sql  .= $virgula." j23_recdst = $this->j23_recdst ";
       $virgula = ",";
       if(trim($this->j23_recdst) == null ){ 
         $this->erro_sql = " Campo Receita Destino nao Informado.";
         $this->erro_campo = "j23_recdst";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_tipo"])){ 
       $sql  .= $virgula." j23_tipo = $this->j23_tipo ";
       $virgula = ",";
       if(trim($this->j23_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "j23_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j23_sequencial!=null){
       $sql .= " j23_sequencial = $this->j23_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j23_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10820,'$this->j23_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1863,10820,'".AddSlashes(pg_result($resaco,$conresaco,'j23_sequencial'))."','$this->j23_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_matric"]))
           $resac = db_query("insert into db_acount values($acount,1863,592,'".AddSlashes(pg_result($resaco,$conresaco,'j23_matric'))."','$this->j23_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1863,591,'".AddSlashes(pg_result($resaco,$conresaco,'j23_anousu'))."','$this->j23_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_recorg"]))
           $resac = db_query("insert into db_acount values($acount,1863,10822,'".AddSlashes(pg_result($resaco,$conresaco,'j23_recorg'))."','$this->j23_recorg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_recdst"]))
           $resac = db_query("insert into db_acount values($acount,1863,10823,'".AddSlashes(pg_result($resaco,$conresaco,'j23_recdst'))."','$this->j23_recdst',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1863,12595,'".AddSlashes(pg_result($resaco,$conresaco,'j23_tipo'))."','$this->j23_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuracao de calculo por matricula nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuracao de calculo por matricula nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j23_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j23_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10820,'$j23_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1863,10820,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1863,592,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1863,591,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1863,10822,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_recorg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1863,10823,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_recdst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1863,12595,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptucalcconfrec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j23_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j23_sequencial = $j23_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuracao de calculo por matricula nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuracao de calculo por matricula nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j23_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptucalcconfrec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalcconfrec ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptucalcconfrec.j23_matric";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = iptucalcconfrec.j23_recorg";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql2 = "";
     if($dbwhere==""){
       if($j23_sequencial!=null ){
         $sql2 .= " where iptucalcconfrec.j23_sequencial = $j23_sequencial "; 
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
   function sql_query_file ( $j23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalcconfrec ";
     $sql2 = "";
     if($dbwhere==""){
       if($j23_sequencial!=null ){
         $sql2 .= " where iptucalcconfrec.j23_sequencial = $j23_sequencial "; 
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