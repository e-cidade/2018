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

//MODULO: fiscal
//CLASSE DA ENTIDADE levvalorpgtos
class cl_levvalorpgtos { 
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
   var $y68_sequencia = 0; 
   var $y68_seq = 0; 
   var $y68_valor = 0; 
   var $y68_pgto_dia = null; 
   var $y68_pgto_mes = null; 
   var $y68_pgto_ano = null; 
   var $y68_pgto = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y68_sequencia = int4 = Sequencial 
                 y68_seq = int4 = seequencia 
                 y68_valor = float8 = Valor 
                 y68_pgto = date = Pagamento 
                 ";
   //funcao construtor da classe 
   function cl_levvalorpgtos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("levvalorpgtos"); 
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
       $this->y68_sequencia = ($this->y68_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["y68_sequencia"]:$this->y68_sequencia);
       $this->y68_seq = ($this->y68_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["y68_seq"]:$this->y68_seq);
       $this->y68_valor = ($this->y68_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["y68_valor"]:$this->y68_valor);
       if($this->y68_pgto == ""){
         $this->y68_pgto_dia = ($this->y68_pgto_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y68_pgto_dia"]:$this->y68_pgto_dia);
         $this->y68_pgto_mes = ($this->y68_pgto_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y68_pgto_mes"]:$this->y68_pgto_mes);
         $this->y68_pgto_ano = ($this->y68_pgto_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y68_pgto_ano"]:$this->y68_pgto_ano);
         if($this->y68_pgto_dia != ""){
            $this->y68_pgto = $this->y68_pgto_ano."-".$this->y68_pgto_mes."-".$this->y68_pgto_dia;
         }
       }
     }else{
       $this->y68_sequencia = ($this->y68_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["y68_sequencia"]:$this->y68_sequencia);
       $this->y68_seq = ($this->y68_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["y68_seq"]:$this->y68_seq);
     }
   }
   // funcao para inclusao
   function incluir ($y68_sequencia,$y68_seq){ 
      $this->atualizacampos();
     if($this->y68_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "y68_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y68_pgto == null ){ 
       $this->erro_sql = " Campo Pagamento nao Informado.";
       $this->erro_campo = "y68_pgto_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->y68_sequencia = $y68_sequencia; 
       $this->y68_seq = $y68_seq; 
     if(($this->y68_sequencia == null) || ($this->y68_sequencia == "") ){ 
       $this->erro_sql = " Campo y68_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->y68_seq == null) || ($this->y68_seq == "") ){ 
       $this->erro_sql = " Campo y68_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into levvalorpgtos(
                                       y68_sequencia 
                                      ,y68_seq 
                                      ,y68_valor 
                                      ,y68_pgto 
                       )
                values (
                                $this->y68_sequencia 
                               ,$this->y68_seq 
                               ,$this->y68_valor 
                               ,".($this->y68_pgto == "null" || $this->y68_pgto == ""?"null":"'".$this->y68_pgto."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "valores dos pagamentos ($this->y68_sequencia."-".$this->y68_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "valores dos pagamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "valores dos pagamentos ($this->y68_sequencia."-".$this->y68_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y68_sequencia."-".$this->y68_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y68_sequencia,$this->y68_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5099,'$this->y68_sequencia','I')");
       $resac = db_query("insert into db_acountkey values($acount,5100,'$this->y68_seq','I')");
       $resac = db_query("insert into db_acount values($acount,726,5099,'','".AddSlashes(pg_result($resaco,0,'y68_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,726,5100,'','".AddSlashes(pg_result($resaco,0,'y68_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,726,5101,'','".AddSlashes(pg_result($resaco,0,'y68_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,726,5102,'','".AddSlashes(pg_result($resaco,0,'y68_pgto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y68_sequencia=null,$y68_seq=null) { 
      $this->atualizacampos();
     $sql = " update levvalorpgtos set ";
     $virgula = "";
     if(trim($this->y68_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y68_sequencia"])){ 
       $sql  .= $virgula." y68_sequencia = $this->y68_sequencia ";
       $virgula = ",";
       if(trim($this->y68_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "y68_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y68_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y68_seq"])){ 
       $sql  .= $virgula." y68_seq = $this->y68_seq ";
       $virgula = ",";
       if(trim($this->y68_seq) == null ){ 
         $this->erro_sql = " Campo seequencia nao Informado.";
         $this->erro_campo = "y68_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y68_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y68_valor"])){ 
       $sql  .= $virgula." y68_valor = $this->y68_valor ";
       $virgula = ",";
       if(trim($this->y68_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "y68_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y68_pgto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y68_pgto_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y68_pgto_dia"] !="") ){ 
       $sql  .= $virgula." y68_pgto = '$this->y68_pgto' ";
       $virgula = ",";
       if(trim($this->y68_pgto) == null ){ 
         $this->erro_sql = " Campo Pagamento nao Informado.";
         $this->erro_campo = "y68_pgto_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y68_pgto_dia"])){ 
         $sql  .= $virgula." y68_pgto = null ";
         $virgula = ",";
         if(trim($this->y68_pgto) == null ){ 
           $this->erro_sql = " Campo Pagamento nao Informado.";
           $this->erro_campo = "y68_pgto_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($y68_sequencia!=null){
       $sql .= " y68_sequencia = $this->y68_sequencia";
     }
     if($y68_seq!=null){
       $sql .= " and  y68_seq = $this->y68_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y68_sequencia,$this->y68_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5099,'$this->y68_sequencia','A')");
         $resac = db_query("insert into db_acountkey values($acount,5100,'$this->y68_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y68_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,726,5099,'".AddSlashes(pg_result($resaco,$conresaco,'y68_sequencia'))."','$this->y68_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y68_seq"]))
           $resac = db_query("insert into db_acount values($acount,726,5100,'".AddSlashes(pg_result($resaco,$conresaco,'y68_seq'))."','$this->y68_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y68_valor"]))
           $resac = db_query("insert into db_acount values($acount,726,5101,'".AddSlashes(pg_result($resaco,$conresaco,'y68_valor'))."','$this->y68_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y68_pgto"]))
           $resac = db_query("insert into db_acount values($acount,726,5102,'".AddSlashes(pg_result($resaco,$conresaco,'y68_pgto'))."','$this->y68_pgto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "valores dos pagamentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y68_sequencia."-".$this->y68_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "valores dos pagamentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y68_sequencia."-".$this->y68_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y68_sequencia."-".$this->y68_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y68_sequencia=null,$y68_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y68_sequencia,$y68_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5099,'$y68_sequencia','E')");
         $resac = db_query("insert into db_acountkey values($acount,5100,'$y68_seq','E')");
         $resac = db_query("insert into db_acount values($acount,726,5099,'','".AddSlashes(pg_result($resaco,$iresaco,'y68_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,726,5100,'','".AddSlashes(pg_result($resaco,$iresaco,'y68_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,726,5101,'','".AddSlashes(pg_result($resaco,$iresaco,'y68_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,726,5102,'','".AddSlashes(pg_result($resaco,$iresaco,'y68_pgto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from levvalorpgtos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y68_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y68_sequencia = $y68_sequencia ";
        }
        if($y68_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y68_seq = $y68_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "valores dos pagamentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y68_sequencia."-".$y68_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "valores dos pagamentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y68_sequencia."-".$y68_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y68_sequencia."-".$y68_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:levvalorpgtos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y68_sequencia=null,$y68_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from levvalorpgtos ";
     $sql .= "      inner join levvalor  on  levvalor.y63_sequencia = levvalorpgtos.y68_sequencia";
     $sql .= "      inner join levanta  on  levanta.y60_codlev = levvalor.y63_codlev";
     $sql2 = "";
     if($dbwhere==""){
       if($y68_sequencia!=null ){
         $sql2 .= " where levvalorpgtos.y68_sequencia = $y68_sequencia "; 
       } 
       if($y68_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " levvalorpgtos.y68_seq = $y68_seq "; 
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
   function sql_query_file ( $y68_sequencia=null,$y68_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from levvalorpgtos ";
     $sql2 = "";
     if($dbwhere==""){
       if($y68_sequencia!=null ){
         $sql2 .= " where levvalorpgtos.y68_sequencia = $y68_sequencia "; 
       } 
       if($y68_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " levvalorpgtos.y68_seq = $y68_seq "; 
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