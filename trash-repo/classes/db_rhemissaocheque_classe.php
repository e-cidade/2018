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
//CLASSE DA ENTIDADE rhemissaocheque
class cl_rhemissaocheque { 
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
   var $r15_sequencial = 0; 
   var $r15_descricao = null; 
   var $r15_idusuario = 0; 
   var $r15_dtgeracao_dia = null; 
   var $r15_dtgeracao_mes = null; 
   var $r15_dtgeracao_ano = null; 
   var $r15_dtgeracao = null; 
   var $r15_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r15_sequencial = int4 = Sequencial 
                 r15_descricao = varchar(40) = Descrição 
                 r15_idusuario = int4 = ID usuário 
                 r15_dtgeracao = date = Data Geração 
                 r15_hora = char(5) = Hora Geração 
                 ";
   //funcao construtor da classe 
   function cl_rhemissaocheque() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhemissaocheque"); 
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
       $this->r15_sequencial = ($this->r15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["r15_sequencial"]:$this->r15_sequencial);
       $this->r15_descricao = ($this->r15_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["r15_descricao"]:$this->r15_descricao);
       $this->r15_idusuario = ($this->r15_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["r15_idusuario"]:$this->r15_idusuario);
       if($this->r15_dtgeracao == ""){
         $this->r15_dtgeracao_dia = ($this->r15_dtgeracao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r15_dtgeracao_dia"]:$this->r15_dtgeracao_dia);
         $this->r15_dtgeracao_mes = ($this->r15_dtgeracao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r15_dtgeracao_mes"]:$this->r15_dtgeracao_mes);
         $this->r15_dtgeracao_ano = ($this->r15_dtgeracao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r15_dtgeracao_ano"]:$this->r15_dtgeracao_ano);
         if($this->r15_dtgeracao_dia != ""){
            $this->r15_dtgeracao = $this->r15_dtgeracao_ano."-".$this->r15_dtgeracao_mes."-".$this->r15_dtgeracao_dia;
         }
       }
       $this->r15_hora = ($this->r15_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["r15_hora"]:$this->r15_hora);
     }else{
       $this->r15_sequencial = ($this->r15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["r15_sequencial"]:$this->r15_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($r15_sequencial){ 
      $this->atualizacampos();
     if($this->r15_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "r15_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r15_idusuario == null ){ 
       $this->erro_sql = " Campo ID usuário nao Informado.";
       $this->erro_campo = "r15_idusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r15_dtgeracao == null ){ 
       $this->erro_sql = " Campo Data Geração nao Informado.";
       $this->erro_campo = "r15_dtgeracao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r15_hora == null ){ 
       $this->erro_sql = " Campo Hora Geração nao Informado.";
       $this->erro_campo = "r15_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($r15_sequencial == "" || $r15_sequencial == null ){
       $result = db_query("select nextval('rhemissaocheque_r15_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhemissaocheque_r15_sequencial_seq do campo: r15_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->r15_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhemissaocheque_r15_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $r15_sequencial)){
         $this->erro_sql = " Campo r15_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->r15_sequencial = $r15_sequencial; 
       }
     }
     if(($this->r15_sequencial == null) || ($this->r15_sequencial == "") ){ 
       $this->erro_sql = " Campo r15_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhemissaocheque(
                                       r15_sequencial 
                                      ,r15_descricao 
                                      ,r15_idusuario 
                                      ,r15_dtgeracao 
                                      ,r15_hora 
                       )
                values (
                                $this->r15_sequencial 
                               ,'$this->r15_descricao' 
                               ,$this->r15_idusuario 
                               ,".($this->r15_dtgeracao == "null" || $this->r15_dtgeracao == ""?"null":"'".$this->r15_dtgeracao."'")." 
                               ,'$this->r15_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhemissaocheque ($this->r15_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhemissaocheque já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhemissaocheque ($this->r15_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r15_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r15_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14059,'$this->r15_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2472,14059,'','".AddSlashes(pg_result($resaco,0,'r15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2472,14060,'','".AddSlashes(pg_result($resaco,0,'r15_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2472,14061,'','".AddSlashes(pg_result($resaco,0,'r15_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2472,14062,'','".AddSlashes(pg_result($resaco,0,'r15_dtgeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2472,14063,'','".AddSlashes(pg_result($resaco,0,'r15_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r15_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhemissaocheque set ";
     $virgula = "";
     if(trim($this->r15_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r15_sequencial"])){ 
       $sql  .= $virgula." r15_sequencial = $this->r15_sequencial ";
       $virgula = ",";
       if(trim($this->r15_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "r15_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r15_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r15_descricao"])){ 
       $sql  .= $virgula." r15_descricao = '$this->r15_descricao' ";
       $virgula = ",";
       if(trim($this->r15_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "r15_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r15_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r15_idusuario"])){ 
       $sql  .= $virgula." r15_idusuario = $this->r15_idusuario ";
       $virgula = ",";
       if(trim($this->r15_idusuario) == null ){ 
         $this->erro_sql = " Campo ID usuário nao Informado.";
         $this->erro_campo = "r15_idusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r15_dtgeracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r15_dtgeracao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r15_dtgeracao_dia"] !="") ){ 
       $sql  .= $virgula." r15_dtgeracao = '$this->r15_dtgeracao' ";
       $virgula = ",";
       if(trim($this->r15_dtgeracao) == null ){ 
         $this->erro_sql = " Campo Data Geração nao Informado.";
         $this->erro_campo = "r15_dtgeracao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r15_dtgeracao_dia"])){ 
         $sql  .= $virgula." r15_dtgeracao = null ";
         $virgula = ",";
         if(trim($this->r15_dtgeracao) == null ){ 
           $this->erro_sql = " Campo Data Geração nao Informado.";
           $this->erro_campo = "r15_dtgeracao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r15_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r15_hora"])){ 
       $sql  .= $virgula." r15_hora = '$this->r15_hora' ";
       $virgula = ",";
       if(trim($this->r15_hora) == null ){ 
         $this->erro_sql = " Campo Hora Geração nao Informado.";
         $this->erro_campo = "r15_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r15_sequencial!=null){
       $sql .= " r15_sequencial = $this->r15_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r15_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14059,'$this->r15_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r15_sequencial"]) || $this->r15_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2472,14059,'".AddSlashes(pg_result($resaco,$conresaco,'r15_sequencial'))."','$this->r15_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r15_descricao"]) || $this->r15_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2472,14060,'".AddSlashes(pg_result($resaco,$conresaco,'r15_descricao'))."','$this->r15_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r15_idusuario"]) || $this->r15_idusuario != "")
           $resac = db_query("insert into db_acount values($acount,2472,14061,'".AddSlashes(pg_result($resaco,$conresaco,'r15_idusuario'))."','$this->r15_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r15_dtgeracao"]) || $this->r15_dtgeracao != "")
           $resac = db_query("insert into db_acount values($acount,2472,14062,'".AddSlashes(pg_result($resaco,$conresaco,'r15_dtgeracao'))."','$this->r15_dtgeracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r15_hora"]) || $this->r15_hora != "")
           $resac = db_query("insert into db_acount values($acount,2472,14063,'".AddSlashes(pg_result($resaco,$conresaco,'r15_hora'))."','$this->r15_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhemissaocheque nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhemissaocheque nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r15_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r15_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14059,'$r15_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2472,14059,'','".AddSlashes(pg_result($resaco,$iresaco,'r15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2472,14060,'','".AddSlashes(pg_result($resaco,$iresaco,'r15_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2472,14061,'','".AddSlashes(pg_result($resaco,$iresaco,'r15_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2472,14062,'','".AddSlashes(pg_result($resaco,$iresaco,'r15_dtgeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2472,14063,'','".AddSlashes(pg_result($resaco,$iresaco,'r15_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhemissaocheque
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r15_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r15_sequencial = $r15_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhemissaocheque nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhemissaocheque nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r15_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhemissaocheque";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhemissaocheque ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rhemissaocheque.r15_idusuario";
     $sql2 = "";
     if($dbwhere==""){
       if($r15_sequencial!=null ){
         $sql2 .= " where rhemissaocheque.r15_sequencial = $r15_sequencial "; 
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
   function sql_query_file ( $r15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhemissaocheque ";
     $sql2 = "";
     if($dbwhere==""){
       if($r15_sequencial!=null ){
         $sql2 .= " where rhemissaocheque.r15_sequencial = $r15_sequencial "; 
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
  
   function sql_query_item( $r15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhemissaocheque ";
     $sql .= "      inner join db_usuarios         on db_usuarios.id_usuario                = rhemissaocheque.r15_idusuario  ";
     $sql .= "      left  join rhemissaochequeitem on rhemissaochequeitem.r18_emissaocheque = rhemissaocheque.r15_sequencial ";
     $sql2 = "";
     if($dbwhere==""){
       if($r15_sequencial!=null ){
         $sql2 .= " where rhemissaocheque.r15_sequencial = $r15_sequencial "; 
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