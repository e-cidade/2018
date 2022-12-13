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

//MODULO: caixa
//CLASSE DA ENTIDADE debcontaarquivoregmov
class cl_debcontaarquivoregmov { 
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
   var $d75_sequencial = 0; 
   var $d75_codigo = 0; 
   var $d75_venc_dia = null; 
   var $d75_venc_mes = null; 
   var $d75_venc_ano = null; 
   var $d75_venc = null; 
   var $d75_valor = 0; 
   var $d75_numpar = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d75_sequencial = int4 = Codigo sequencial 
                 d75_codigo = int4 = Codigo sequencial 
                 d75_venc = date = Vencimento 
                 d75_valor = float8 = Valor 
                 d75_numpar = int4 = Parcela 
                 ";
   //funcao construtor da classe 
   function cl_debcontaarquivoregmov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("debcontaarquivoregmov"); 
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
       $this->d75_sequencial = ($this->d75_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["d75_sequencial"]:$this->d75_sequencial);
       $this->d75_codigo = ($this->d75_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d75_codigo"]:$this->d75_codigo);
       if($this->d75_venc == ""){
         $this->d75_venc_dia = ($this->d75_venc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["d75_venc_dia"]:$this->d75_venc_dia);
         $this->d75_venc_mes = ($this->d75_venc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["d75_venc_mes"]:$this->d75_venc_mes);
         $this->d75_venc_ano = ($this->d75_venc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["d75_venc_ano"]:$this->d75_venc_ano);
         if($this->d75_venc_dia != ""){
            $this->d75_venc = $this->d75_venc_ano."-".$this->d75_venc_mes."-".$this->d75_venc_dia;
         }
       }
       $this->d75_valor = ($this->d75_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["d75_valor"]:$this->d75_valor);
       $this->d75_numpar = ($this->d75_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["d75_numpar"]:$this->d75_numpar);
     }else{
       $this->d75_sequencial = ($this->d75_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["d75_sequencial"]:$this->d75_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($d75_sequencial){ 
      $this->atualizacampos();
     if($this->d75_codigo == null ){ 
       $this->erro_sql = " Campo Codigo sequencial nao Informado.";
       $this->erro_campo = "d75_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d75_venc == null ){ 
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "d75_venc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d75_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "d75_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d75_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "d75_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($d75_sequencial == "" || $d75_sequencial == null ){
       $result = db_query("select nextval('debcontaarquivoregmov_d75_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: debcontaarquivoregmov_d75_sequencial_seq do campo: d75_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->d75_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from debcontaarquivoregmov_d75_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $d75_sequencial)){
         $this->erro_sql = " Campo d75_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->d75_sequencial = $d75_sequencial; 
       }
     }
     if(($this->d75_sequencial == null) || ($this->d75_sequencial == "") ){ 
       $this->erro_sql = " Campo d75_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into debcontaarquivoregmov(
                                       d75_sequencial 
                                      ,d75_codigo 
                                      ,d75_venc 
                                      ,d75_valor 
                                      ,d75_numpar 
                       )
                values (
                                $this->d75_sequencial 
                               ,$this->d75_codigo 
                               ,".($this->d75_venc == "null" || $this->d75_venc == ""?"null":"'".$this->d75_venc."'")." 
                               ,$this->d75_valor 
                               ,$this->d75_numpar 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentos dos arquivos de debito em conta ($this->d75_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentos dos arquivos de debito em conta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentos dos arquivos de debito em conta ($this->d75_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d75_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d75_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7988,'$this->d75_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1342,7988,'','".AddSlashes(pg_result($resaco,0,'d75_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1342,7989,'','".AddSlashes(pg_result($resaco,0,'d75_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1342,7990,'','".AddSlashes(pg_result($resaco,0,'d75_venc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1342,7991,'','".AddSlashes(pg_result($resaco,0,'d75_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1342,8266,'','".AddSlashes(pg_result($resaco,0,'d75_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d75_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update debcontaarquivoregmov set ";
     $virgula = "";
     if(trim($this->d75_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d75_sequencial"])){ 
       $sql  .= $virgula." d75_sequencial = $this->d75_sequencial ";
       $virgula = ",";
       if(trim($this->d75_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "d75_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d75_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d75_codigo"])){ 
       $sql  .= $virgula." d75_codigo = $this->d75_codigo ";
       $virgula = ",";
       if(trim($this->d75_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "d75_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d75_venc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d75_venc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["d75_venc_dia"] !="") ){ 
       $sql  .= $virgula." d75_venc = '$this->d75_venc' ";
       $virgula = ",";
       if(trim($this->d75_venc) == null ){ 
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "d75_venc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["d75_venc_dia"])){ 
         $sql  .= $virgula." d75_venc = null ";
         $virgula = ",";
         if(trim($this->d75_venc) == null ){ 
           $this->erro_sql = " Campo Vencimento nao Informado.";
           $this->erro_campo = "d75_venc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->d75_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d75_valor"])){ 
       $sql  .= $virgula." d75_valor = $this->d75_valor ";
       $virgula = ",";
       if(trim($this->d75_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "d75_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d75_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d75_numpar"])){ 
       $sql  .= $virgula." d75_numpar = $this->d75_numpar ";
       $virgula = ",";
       if(trim($this->d75_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "d75_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d75_sequencial!=null){
       $sql .= " d75_sequencial = $this->d75_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d75_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7988,'$this->d75_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d75_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1342,7988,'".AddSlashes(pg_result($resaco,$conresaco,'d75_sequencial'))."','$this->d75_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d75_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1342,7989,'".AddSlashes(pg_result($resaco,$conresaco,'d75_codigo'))."','$this->d75_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d75_venc"]))
           $resac = db_query("insert into db_acount values($acount,1342,7990,'".AddSlashes(pg_result($resaco,$conresaco,'d75_venc'))."','$this->d75_venc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d75_valor"]))
           $resac = db_query("insert into db_acount values($acount,1342,7991,'".AddSlashes(pg_result($resaco,$conresaco,'d75_valor'))."','$this->d75_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d75_numpar"]))
           $resac = db_query("insert into db_acount values($acount,1342,8266,'".AddSlashes(pg_result($resaco,$conresaco,'d75_numpar'))."','$this->d75_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentos dos arquivos de debito em conta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d75_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentos dos arquivos de debito em conta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d75_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d75_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7988,'$d75_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1342,7988,'','".AddSlashes(pg_result($resaco,$iresaco,'d75_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1342,7989,'','".AddSlashes(pg_result($resaco,$iresaco,'d75_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1342,7990,'','".AddSlashes(pg_result($resaco,$iresaco,'d75_venc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1342,7991,'','".AddSlashes(pg_result($resaco,$iresaco,'d75_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1342,8266,'','".AddSlashes(pg_result($resaco,$iresaco,'d75_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from debcontaarquivoregmov
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d75_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d75_sequencial = $d75_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentos dos arquivos de debito em conta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d75_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentos dos arquivos de debito em conta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d75_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:debcontaarquivoregmov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from debcontaarquivoregmov ";
     $sql .= "      inner join debcontaarquivoreg  on  debcontaarquivoreg.d73_sequencial = debcontaarquivoregmov.d75_codigo";
     $sql .= "      inner join debcontaarquivo  on  debcontaarquivo.d72_codigo = debcontaarquivoreg.d73_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($d75_sequencial!=null ){
         $sql2 .= " where debcontaarquivoregmov.d75_sequencial = $d75_sequencial "; 
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
   function sql_query_file ( $d75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from debcontaarquivoregmov ";
     $sql2 = "";
     if($dbwhere==""){
       if($d75_sequencial!=null ){
         $sql2 .= " where debcontaarquivoregmov.d75_sequencial = $d75_sequencial "; 
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