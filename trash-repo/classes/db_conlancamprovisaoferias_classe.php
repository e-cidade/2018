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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancamprovisaoferias
class cl_conlancamprovisaoferias { 
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
   var $c101_sequencial = 0; 
   var $c101_codlan = 0; 
   var $c101_instit = 0; 
   var $c101_mes = 0; 
   var $c101_ano = 0; 
   var $c101_escrituraprovisao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c101_sequencial = int4 = Sequencial 
                 c101_codlan = int4 = Código Lançamento 
                 c101_instit = int4 = Cod. Instituição 
                 c101_mes = int4 = Mês 
                 c101_ano = int4 = Ano 
                 c101_escrituraprovisao = int4 = Escritura Provisao 
                 ";
   //funcao construtor da classe 
   function cl_conlancamprovisaoferias() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancamprovisaoferias"); 
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
       $this->c101_sequencial = ($this->c101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c101_sequencial"]:$this->c101_sequencial);
       $this->c101_codlan = ($this->c101_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c101_codlan"]:$this->c101_codlan);
       $this->c101_instit = ($this->c101_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c101_instit"]:$this->c101_instit);
       $this->c101_mes = ($this->c101_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c101_mes"]:$this->c101_mes);
       $this->c101_ano = ($this->c101_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c101_ano"]:$this->c101_ano);
       $this->c101_escrituraprovisao = ($this->c101_escrituraprovisao == ""?@$GLOBALS["HTTP_POST_VARS"]["c101_escrituraprovisao"]:$this->c101_escrituraprovisao);
     }else{
       $this->c101_sequencial = ($this->c101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c101_sequencial"]:$this->c101_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c101_sequencial){ 
      $this->atualizacampos();
     if($this->c101_codlan == null ){ 
       $this->erro_sql = " Campo Código Lançamento nao Informado.";
       $this->erro_campo = "c101_codlan";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c101_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "c101_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c101_mes == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "c101_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c101_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "c101_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c101_escrituraprovisao == null ){ 
       $this->erro_sql = " Campo Escritura Provisao nao Informado.";
       $this->erro_campo = "c101_escrituraprovisao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c101_sequencial == "" || $c101_sequencial == null ){
       $result = db_query("select nextval('conlancamprovisaoferias_c101_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conlancamprovisaoferias_c101_sequencial_seq do campo: c101_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c101_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conlancamprovisaoferias_c101_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c101_sequencial)){
         $this->erro_sql = " Campo c101_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c101_sequencial = $c101_sequencial; 
       }
     }
     if(($this->c101_sequencial == null) || ($this->c101_sequencial == "") ){ 
       $this->erro_sql = " Campo c101_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancamprovisaoferias(
                                       c101_sequencial 
                                      ,c101_codlan 
                                      ,c101_instit 
                                      ,c101_mes 
                                      ,c101_ano 
                                      ,c101_escrituraprovisao 
                       )
                values (
                                $this->c101_sequencial 
                               ,$this->c101_codlan 
                               ,$this->c101_instit 
                               ,$this->c101_mes 
                               ,$this->c101_ano 
                               ,$this->c101_escrituraprovisao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lançamento contábil de provisão de férias ($this->c101_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lançamento contábil de provisão de férias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lançamento contábil de provisão de férias ($this->c101_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c101_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c101_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19458,'$this->c101_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3454,19458,'','".AddSlashes(pg_result($resaco,0,'c101_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3454,19462,'','".AddSlashes(pg_result($resaco,0,'c101_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3454,19461,'','".AddSlashes(pg_result($resaco,0,'c101_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3454,19459,'','".AddSlashes(pg_result($resaco,0,'c101_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3454,19460,'','".AddSlashes(pg_result($resaco,0,'c101_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3454,19515,'','".AddSlashes(pg_result($resaco,0,'c101_escrituraprovisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c101_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update conlancamprovisaoferias set ";
     $virgula = "";
     if(trim($this->c101_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c101_sequencial"])){ 
       $sql  .= $virgula." c101_sequencial = $this->c101_sequencial ";
       $virgula = ",";
       if(trim($this->c101_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "c101_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c101_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c101_codlan"])){ 
       $sql  .= $virgula." c101_codlan = $this->c101_codlan ";
       $virgula = ",";
       if(trim($this->c101_codlan) == null ){ 
         $this->erro_sql = " Campo Código Lançamento nao Informado.";
         $this->erro_campo = "c101_codlan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c101_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c101_instit"])){ 
       $sql  .= $virgula." c101_instit = $this->c101_instit ";
       $virgula = ",";
       if(trim($this->c101_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "c101_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c101_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c101_mes"])){ 
       $sql  .= $virgula." c101_mes = $this->c101_mes ";
       $virgula = ",";
       if(trim($this->c101_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "c101_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c101_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c101_ano"])){ 
       $sql  .= $virgula." c101_ano = $this->c101_ano ";
       $virgula = ",";
       if(trim($this->c101_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "c101_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c101_escrituraprovisao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c101_escrituraprovisao"])){ 
       $sql  .= $virgula." c101_escrituraprovisao = $this->c101_escrituraprovisao ";
       $virgula = ",";
       if(trim($this->c101_escrituraprovisao) == null ){ 
         $this->erro_sql = " Campo Escritura Provisao nao Informado.";
         $this->erro_campo = "c101_escrituraprovisao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c101_sequencial!=null){
       $sql .= " c101_sequencial = $this->c101_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c101_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19458,'$this->c101_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c101_sequencial"]) || $this->c101_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3454,19458,'".AddSlashes(pg_result($resaco,$conresaco,'c101_sequencial'))."','$this->c101_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c101_codlan"]) || $this->c101_codlan != "")
           $resac = db_query("insert into db_acount values($acount,3454,19462,'".AddSlashes(pg_result($resaco,$conresaco,'c101_codlan'))."','$this->c101_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c101_instit"]) || $this->c101_instit != "")
           $resac = db_query("insert into db_acount values($acount,3454,19461,'".AddSlashes(pg_result($resaco,$conresaco,'c101_instit'))."','$this->c101_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c101_mes"]) || $this->c101_mes != "")
           $resac = db_query("insert into db_acount values($acount,3454,19459,'".AddSlashes(pg_result($resaco,$conresaco,'c101_mes'))."','$this->c101_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c101_ano"]) || $this->c101_ano != "")
           $resac = db_query("insert into db_acount values($acount,3454,19460,'".AddSlashes(pg_result($resaco,$conresaco,'c101_ano'))."','$this->c101_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c101_escrituraprovisao"]) || $this->c101_escrituraprovisao != "")
           $resac = db_query("insert into db_acount values($acount,3454,19515,'".AddSlashes(pg_result($resaco,$conresaco,'c101_escrituraprovisao'))."','$this->c101_escrituraprovisao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamento contábil de provisão de férias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c101_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamento contábil de provisão de férias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c101_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c101_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19458,'$c101_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3454,19458,'','".AddSlashes(pg_result($resaco,$iresaco,'c101_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3454,19462,'','".AddSlashes(pg_result($resaco,$iresaco,'c101_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3454,19461,'','".AddSlashes(pg_result($resaco,$iresaco,'c101_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3454,19459,'','".AddSlashes(pg_result($resaco,$iresaco,'c101_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3454,19460,'','".AddSlashes(pg_result($resaco,$iresaco,'c101_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3454,19515,'','".AddSlashes(pg_result($resaco,$iresaco,'c101_escrituraprovisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conlancamprovisaoferias
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c101_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c101_sequencial = $c101_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamento contábil de provisão de férias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c101_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamento contábil de provisão de férias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c101_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conlancamprovisaoferias";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancamprovisaoferias ";
     $sql .= "      inner join conlancam         on conlancam.c70_codlan = conlancamprovisaoferias.c101_codlan";
     $sql .= "      inner join escrituraprovisao on escrituraprovisao.c102_sequencial = conlancamprovisaoferias.c101_escrituraprovisao";
     $sql .= "      inner join db_config         on db_config.codigo = escrituraprovisao.c102_instit";
     $sql .= "      inner join db_tipoinstit     on db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join cgm               on cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_usuarios       on db_usuarios.id_usuario = escrituraprovisao.c102_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($c101_sequencial!=null ){
         $sql2 .= " where conlancamprovisaoferias.c101_sequencial = $c101_sequencial "; 
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
   function sql_query_file ( $c101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancamprovisaoferias ";
     $sql2 = "";
     if($dbwhere==""){
       if($c101_sequencial!=null ){
         $sql2 .= " where conlancamprovisaoferias.c101_sequencial = $c101_sequencial "; 
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