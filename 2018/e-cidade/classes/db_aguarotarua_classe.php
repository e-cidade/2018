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

//MODULO: Agua
//CLASSE DA ENTIDADE aguarotarua
class cl_aguarotarua { 
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
   var $x07_codrotarua = 0; 
   var $x07_codrota = 0; 
   var $x07_codrua = 0; 
   var $x07_ordem = 0; 
   var $x07_nroini = 0; 
   var $x07_nrofim = 0;
   var $x07_orientacao = null;
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x07_codrotarua = int4 = Código 
                 x07_codrota = int4 = Rota 
                 x07_codrua = int4 = Logradouro 
                 x07_ordem = int4 = Ordem 
                 x07_nroini = int4 = Número Inicial 
                 x07_nrofim = int4 = Número Final
                 x07_orientacao = char(1) = Orientação
                 ";
   //funcao construtor da classe 
   function cl_aguarotarua() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguarotarua"); 
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
     if ($exclusao == false) {
       $this->x07_codrotarua = ($this->x07_codrotarua == ""?@$GLOBALS["HTTP_POST_VARS"]["x07_codrotarua"]:$this->x07_codrotarua);
       $this->x07_codrota = ($this->x07_codrota == ""?@$GLOBALS["HTTP_POST_VARS"]["x07_codrota"]:$this->x07_codrota);
       $this->x07_codrua = ($this->x07_codrua == ""?@$GLOBALS["HTTP_POST_VARS"]["x07_codrua"]:$this->x07_codrua);
       $this->x07_ordem = ($this->x07_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["x07_ordem"]:$this->x07_ordem);
       $this->x07_nroini = ($this->x07_nroini == ""?@$GLOBALS["HTTP_POST_VARS"]["x07_nroini"]:$this->x07_nroini);
       $this->x07_nrofim = ($this->x07_nrofim == ""?@$GLOBALS["HTTP_POST_VARS"]["x07_nrofim"]:$this->x07_nrofim);
       $this->x07_orientacao = ($this->x07_orientacao == ""?@$GLOBALS["HTTP_POST_VARS"]["x07_orientacao"]:$this->x07_orientacao);
     }else{
       $this->x07_codrotarua = ($this->x07_codrotarua == ""?@$GLOBALS["HTTP_POST_VARS"]["x07_codrotarua"]:$this->x07_codrotarua);
       $this->x07_codrota = ($this->x07_codrota == ""?@$GLOBALS["HTTP_POST_VARS"]["x07_codrota"]:$this->x07_codrota);
     }
   }
   // funcao para inclusao
   function incluir ($x07_codrotarua){ 
      $this->atualizacampos();
     if($this->x07_codrua == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "x07_codrua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x07_ordem == null ){ 
       $this->x07_ordem = "0";
     }
     if($this->x07_nroini == null ){ 
       $this->erro_sql = " Campo Número Inicial nao Informado.";
       $this->erro_campo = "x07_nroini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x07_nrofim == null ){ 
       $this->erro_sql = " Campo Número Final nao Informado.";
       $this->erro_campo = "x07_nrofim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x07_codrotarua == "" || $x07_codrotarua == null ){
       $result = db_query("select nextval('aguarotarua_codrotarua_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguarotarua_codrotarua_seq do campo: x07_codrotarua"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x07_codrotarua = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguarotarua_codrotarua_seq");
       if(($result != false) && (pg_result($result,0,0) < $x07_codrotarua)){
         $this->erro_sql = " Campo x07_codrotarua maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x07_codrotarua = $x07_codrotarua; 
       }
     }
     if(($this->x07_codrotarua == null) || ($this->x07_codrotarua == "") ){ 
       $this->erro_sql = " Campo x07_codrotarua nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguarotarua(
                                       x07_codrotarua 
                                      ,x07_codrota 
                                      ,x07_codrua 
                                      ,x07_ordem 
                                      ,x07_nroini 
                                      ,x07_nrofim
                                      ,x07_orientacao 
                       )
                values (
                                $this->x07_codrotarua 
                               ,$this->x07_codrota 
                               ,$this->x07_codrua 
                               ,$this->x07_ordem 
                               ,$this->x07_nroini 
                               ,$this->x07_nrofim
                               ,'$this->x07_orientacao'
                      )";

     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguarotarua ($this->x07_codrotarua) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguarotarua já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguarotarua ($this->x07_codrotarua) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x07_codrotarua;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x07_codrotarua));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8526,'$this->x07_codrotarua','I')");
       $resac = db_query("insert into db_acount values($acount,1450,8526,'','".AddSlashes(pg_result($resaco,0,'x07_codrotarua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1450,8527,'','".AddSlashes(pg_result($resaco,0,'x07_codrota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1450,8528,'','".AddSlashes(pg_result($resaco,0,'x07_codrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1450,8529,'','".AddSlashes(pg_result($resaco,0,'x07_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1450,15451,'','".AddSlashes(pg_result($resaco,0,'x07_nroini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1450,15452,'','".AddSlashes(pg_result($resaco,0,'x07_nrofim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1450,18434,'','".AddSlashes(pg_result($resaco,0,'x07_orientacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x07_codrotarua=null) { 
      $this->atualizacampos();
     $sql = " update aguarotarua set ";
     $virgula = "";
     if(trim($this->x07_codrotarua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x07_codrotarua"])){ 
       $sql  .= $virgula." x07_codrotarua = $this->x07_codrotarua ";
       $virgula = ",";
       if(trim($this->x07_codrotarua) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "x07_codrotarua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x07_codrota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x07_codrota"])){ 
       $sql  .= $virgula." x07_codrota = $this->x07_codrota ";
       $virgula = ",";
       if(trim($this->x07_codrota) == null ){ 
         $this->erro_sql = " Campo Rota nao Informado.";
         $this->erro_campo = "x07_codrota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x07_codrua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x07_codrua"])){ 
       $sql  .= $virgula." x07_codrua = $this->x07_codrua ";
       $virgula = ",";
       if(trim($this->x07_codrua) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "x07_codrua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x07_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x07_ordem"])){ 
        if(trim($this->x07_ordem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x07_ordem"])){ 
           $this->x07_ordem = "0" ; 
        } 
       $sql  .= $virgula." x07_ordem = $this->x07_ordem ";
       $virgula = ",";
     }
     if(trim($this->x07_nroini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x07_nroini"])){ 
       $sql  .= $virgula." x07_nroini = $this->x07_nroini ";
       $virgula = ",";
       if(trim($this->x07_nroini) == null ){ 
         $this->erro_sql = " Campo Número Inicial nao Informado.";
         $this->erro_campo = "x07_nroini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x07_nrofim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x07_nrofim"])){ 
       $sql  .= $virgula." x07_nrofim = $this->x07_nrofim ";
       $virgula = ",";
       if(trim($this->x07_nrofim) == null ){ 
         $this->erro_sql = " Campo Número Final nao Informado.";
         $this->erro_campo = "x07_nrofim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x07_orientacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x07_orientacao"])){ 
       $sql  .= $virgula." x07_orientacao = '$this->x07_orientacao' ";
       $virgula = ",";
       if(trim($this->x07_orientacao) == null ){ 
         $this->erro_sql = " Campo Orientacao nao Informado.";
         $this->erro_campo = "x07_orientacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     
     $sql .= " where ";
     if($x07_codrotarua!=null){
       $sql .= " x07_codrotarua = $this->x07_codrotarua";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x07_codrotarua));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8526,'$this->x07_codrotarua','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x07_codrotarua"]) || $this->x07_codrotarua != "")
           $resac = db_query("insert into db_acount values($acount,1450,8526,'".AddSlashes(pg_result($resaco,$conresaco,'x07_codrotarua'))."','$this->x07_codrotarua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x07_codrota"]) || $this->x07_codrota != "")
           $resac = db_query("insert into db_acount values($acount,1450,8527,'".AddSlashes(pg_result($resaco,$conresaco,'x07_codrota'))."','$this->x07_codrota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x07_codrua"]) || $this->x07_codrua != "")
           $resac = db_query("insert into db_acount values($acount,1450,8528,'".AddSlashes(pg_result($resaco,$conresaco,'x07_codrua'))."','$this->x07_codrua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x07_ordem"]) || $this->x07_ordem != "")
           $resac = db_query("insert into db_acount values($acount,1450,8529,'".AddSlashes(pg_result($resaco,$conresaco,'x07_ordem'))."','$this->x07_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x07_nroini"]) || $this->x07_nroini != "")
           $resac = db_query("insert into db_acount values($acount,1450,15451,'".AddSlashes(pg_result($resaco,$conresaco,'x07_nroini'))."','$this->x07_nroini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x07_nrofim"]) || $this->x07_nrofim != "")
           $resac = db_query("insert into db_acount values($acount,1450,15452,'".AddSlashes(pg_result($resaco,$conresaco,'x07_nrofim'))."','$this->x07_nrofim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x07_orientacao"]) || $this->x07_orientacao != "")
           $resac = db_query("insert into db_acount values($acount,1450,18434,'".AddSlashes(pg_result($resaco,$conresaco,'x07_orientacao'))."','$this->x07_orientacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguarotarua nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x07_codrotarua;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguarotarua nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x07_codrotarua;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x07_codrotarua;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x07_codrotarua=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x07_codrotarua));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8526,'$x07_codrotarua','E')");
         $resac = db_query("insert into db_acount values($acount,1450,8526,'','".AddSlashes(pg_result($resaco,$iresaco,'x07_codrotarua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1450,8527,'','".AddSlashes(pg_result($resaco,$iresaco,'x07_codrota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1450,8528,'','".AddSlashes(pg_result($resaco,$iresaco,'x07_codrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1450,8529,'','".AddSlashes(pg_result($resaco,$iresaco,'x07_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1450,15451,'','".AddSlashes(pg_result($resaco,$iresaco,'x07_nroini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1450,15452,'','".AddSlashes(pg_result($resaco,$iresaco,'x07_nrofim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1450,18434,'','".AddSlashes(pg_result($resaco,$iresaco,'x07_orientacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         
       }
     }
     $sql = " delete from aguarotarua
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x07_codrotarua != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x07_codrotarua = $x07_codrotarua ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguarotarua nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x07_codrotarua;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguarotarua nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x07_codrotarua;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x07_codrotarua;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguarotarua";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $x07_codrotarua=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguarotarua ";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguarotarua.x07_codrua";
     $sql .= "      inner join aguarota  on  aguarota.x06_codrota = aguarotarua.x07_codrota";
     $sql2 = "";
     if($dbwhere==""){
       if($x07_codrotarua!=null ){
         $sql2 .= " where aguarotarua.x07_codrotarua = $x07_codrotarua "; 
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
   function sql_query_file ( $x07_codrotarua=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguarotarua ";
     $sql2 = "";
     if($dbwhere==""){
       if($x07_codrotarua!=null ){
         $sql2 .= " where aguarotarua.x07_codrotarua = $x07_codrotarua "; 
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