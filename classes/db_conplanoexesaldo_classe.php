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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conplanoexesaldo
class cl_conplanoexesaldo { 
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
   var $c68_anousu = 0; 
   var $c68_reduz = 0; 
   var $c68_mes = 0; 
   var $c68_debito = 0; 
   var $c68_credito = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c68_anousu = int4 = Exercício 
                 c68_reduz = int4 = Reduzido 
                 c68_mes = int4 = Mês 
                 c68_debito = float8 = Valor Débito 
                 c68_credito = float8 = Valor Crédito 
                 ";
   //funcao construtor da classe 
   function cl_conplanoexesaldo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conplanoexesaldo"); 
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
       $this->c68_anousu = ($this->c68_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c68_anousu"]:$this->c68_anousu);
       $this->c68_reduz = ($this->c68_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c68_reduz"]:$this->c68_reduz);
       $this->c68_mes = ($this->c68_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c68_mes"]:$this->c68_mes);
       $this->c68_debito = ($this->c68_debito == ""?@$GLOBALS["HTTP_POST_VARS"]["c68_debito"]:$this->c68_debito);
       $this->c68_credito = ($this->c68_credito == ""?@$GLOBALS["HTTP_POST_VARS"]["c68_credito"]:$this->c68_credito);
     }else{
       $this->c68_anousu = ($this->c68_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c68_anousu"]:$this->c68_anousu);
       $this->c68_reduz = ($this->c68_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c68_reduz"]:$this->c68_reduz);
       $this->c68_mes = ($this->c68_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c68_mes"]:$this->c68_mes);
     }
   }
   // funcao para inclusao
   function incluir ($c68_anousu,$c68_reduz,$c68_mes){ 
      $this->atualizacampos();
     if($this->c68_debito == null ){ 
       $this->erro_sql = " Campo Valor Débito nao Informado.";
       $this->erro_campo = "c68_debito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c68_credito == null ){ 
       $this->erro_sql = " Campo Valor Crédito nao Informado.";
       $this->erro_campo = "c68_credito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c68_anousu = $c68_anousu; 
       $this->c68_reduz = $c68_reduz; 
       $this->c68_mes = $c68_mes; 
     if(($this->c68_anousu == null) || ($this->c68_anousu == "") ){ 
       $this->erro_sql = " Campo c68_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c68_reduz == null) || ($this->c68_reduz == "") ){ 
       $this->erro_sql = " Campo c68_reduz nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c68_mes == null) || ($this->c68_mes == "") ){ 
       $this->erro_sql = " Campo c68_mes nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conplanoexesaldo(
                                       c68_anousu 
                                      ,c68_reduz 
                                      ,c68_mes 
                                      ,c68_debito 
                                      ,c68_credito 
                       )
                values (
                                $this->c68_anousu 
                               ,$this->c68_reduz 
                               ,$this->c68_mes 
                               ,$this->c68_debito 
                               ,$this->c68_credito 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Saldos das contas por exercicio e mes ($this->c68_anousu."-".$this->c68_reduz."-".$this->c68_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Saldos das contas por exercicio e mes já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Saldos das contas por exercicio e mes ($this->c68_anousu."-".$this->c68_reduz."-".$this->c68_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c68_anousu."-".$this->c68_reduz."-".$this->c68_mes;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c68_anousu,$this->c68_reduz,$this->c68_mes));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6246,'$this->c68_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,6247,'$this->c68_reduz','I')");
       $resac = db_query("insert into db_acountkey values($acount,6248,'$this->c68_mes','I')");
       $resac = db_query("insert into db_acount values($acount,1013,6246,'','".AddSlashes(pg_result($resaco,0,'c68_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1013,6247,'','".AddSlashes(pg_result($resaco,0,'c68_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1013,6248,'','".AddSlashes(pg_result($resaco,0,'c68_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1013,6249,'','".AddSlashes(pg_result($resaco,0,'c68_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1013,6250,'','".AddSlashes(pg_result($resaco,0,'c68_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c68_anousu=null,$c68_reduz=null,$c68_mes=null) { 
      $this->atualizacampos();
     $sql = " update conplanoexesaldo set ";
     $virgula = "";
     if(trim($this->c68_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c68_anousu"])){ 
       $sql  .= $virgula." c68_anousu = $this->c68_anousu ";
       $virgula = ",";
       if(trim($this->c68_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "c68_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c68_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c68_reduz"])){ 
       $sql  .= $virgula." c68_reduz = $this->c68_reduz ";
       $virgula = ",";
       if(trim($this->c68_reduz) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "c68_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c68_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c68_mes"])){ 
       $sql  .= $virgula." c68_mes = $this->c68_mes ";
       $virgula = ",";
       if(trim($this->c68_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "c68_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c68_debito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c68_debito"])){ 
       $sql  .= $virgula." c68_debito = $this->c68_debito ";
       $virgula = ",";
       if(trim($this->c68_debito) == null ){ 
         $this->erro_sql = " Campo Valor Débito nao Informado.";
         $this->erro_campo = "c68_debito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c68_credito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c68_credito"])){ 
       $sql  .= $virgula." c68_credito = $this->c68_credito ";
       $virgula = ",";
       if(trim($this->c68_credito) == null ){ 
         $this->erro_sql = " Campo Valor Crédito nao Informado.";
         $this->erro_campo = "c68_credito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c68_anousu!=null){
       $sql .= " c68_anousu = $this->c68_anousu";
     }
     if($c68_reduz!=null){
       $sql .= " and  c68_reduz = $this->c68_reduz";
     }
     if($c68_mes!=null){
       $sql .= " and  c68_mes = $this->c68_mes";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c68_anousu,$this->c68_reduz,$this->c68_mes));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6246,'$this->c68_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,6247,'$this->c68_reduz','A')");
         $resac = db_query("insert into db_acountkey values($acount,6248,'$this->c68_mes','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c68_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1013,6246,'".AddSlashes(pg_result($resaco,$conresaco,'c68_anousu'))."','$this->c68_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c68_reduz"]))
           $resac = db_query("insert into db_acount values($acount,1013,6247,'".AddSlashes(pg_result($resaco,$conresaco,'c68_reduz'))."','$this->c68_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c68_mes"]))
           $resac = db_query("insert into db_acount values($acount,1013,6248,'".AddSlashes(pg_result($resaco,$conresaco,'c68_mes'))."','$this->c68_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c68_debito"]))
           $resac = db_query("insert into db_acount values($acount,1013,6249,'".AddSlashes(pg_result($resaco,$conresaco,'c68_debito'))."','$this->c68_debito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c68_credito"]))
           $resac = db_query("insert into db_acount values($acount,1013,6250,'".AddSlashes(pg_result($resaco,$conresaco,'c68_credito'))."','$this->c68_credito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saldos das contas por exercicio e mes nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c68_anousu."-".$this->c68_reduz."-".$this->c68_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saldos das contas por exercicio e mes nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c68_anousu."-".$this->c68_reduz."-".$this->c68_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c68_anousu."-".$this->c68_reduz."-".$this->c68_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c68_anousu=null,$c68_reduz=null,$c68_mes=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c68_anousu,$c68_reduz,$c68_mes));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6246,'$c68_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,6247,'$c68_reduz','E')");
         $resac = db_query("insert into db_acountkey values($acount,6248,'$c68_mes','E')");
         $resac = db_query("insert into db_acount values($acount,1013,6246,'','".AddSlashes(pg_result($resaco,$iresaco,'c68_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1013,6247,'','".AddSlashes(pg_result($resaco,$iresaco,'c68_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1013,6248,'','".AddSlashes(pg_result($resaco,$iresaco,'c68_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1013,6249,'','".AddSlashes(pg_result($resaco,$iresaco,'c68_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1013,6250,'','".AddSlashes(pg_result($resaco,$iresaco,'c68_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conplanoexesaldo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c68_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c68_anousu = $c68_anousu ";
        }
        if($c68_reduz != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c68_reduz = $c68_reduz ";
        }
        if($c68_mes != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c68_mes = $c68_mes ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saldos das contas por exercicio e mes nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c68_anousu."-".$c68_reduz."-".$c68_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saldos das contas por exercicio e mes nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c68_anousu."-".$c68_reduz."-".$c68_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c68_anousu."-".$c68_reduz."-".$c68_mes;
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
        $this->erro_sql   = "Record Vazio na Tabela:conplanoexesaldo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>