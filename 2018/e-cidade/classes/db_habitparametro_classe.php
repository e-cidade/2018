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

//MODULO: Habitacao
//CLASSE DA ENTIDADE habitparametro
class cl_habitparametro { 
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
   var $ht16_anousu = 0; 
   var $ht16_avaliacao = 0; 
   var $ht16_receitapadrao = 0; 
   var $ht16_qtdparcelaspagamento = 0; 
   var $ht16_diaspadraopagamento = 0; 
   var $ht16_mesescarencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht16_anousu = int4 = Exercício 
                 ht16_avaliacao = int4 = Avaliação 
                 ht16_receitapadrao = int4 = Receita Padrão 
                 ht16_qtdparcelaspagamento = int4 = Parcelas Pagamento 
                 ht16_diaspadraopagamento = int4 = Dias Pagamento 
                 ht16_mesescarencia = int4 = Meses Carência 
                 ";
   //funcao construtor da classe 
   function cl_habitparametro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitparametro"); 
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
       $this->ht16_anousu = ($this->ht16_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["ht16_anousu"]:$this->ht16_anousu);
       $this->ht16_avaliacao = ($this->ht16_avaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht16_avaliacao"]:$this->ht16_avaliacao);
       $this->ht16_receitapadrao = ($this->ht16_receitapadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht16_receitapadrao"]:$this->ht16_receitapadrao);
       $this->ht16_qtdparcelaspagamento = ($this->ht16_qtdparcelaspagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ht16_qtdparcelaspagamento"]:$this->ht16_qtdparcelaspagamento);
       $this->ht16_diaspadraopagamento = ($this->ht16_diaspadraopagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ht16_diaspadraopagamento"]:$this->ht16_diaspadraopagamento);
       $this->ht16_mesescarencia = ($this->ht16_mesescarencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht16_mesescarencia"]:$this->ht16_mesescarencia);
     }else{
       $this->ht16_anousu = ($this->ht16_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["ht16_anousu"]:$this->ht16_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($ht16_anousu){ 
      $this->atualizacampos();
     if($this->ht16_avaliacao == null ){ 
       $this->erro_sql = " Campo Avaliação nao Informado.";
       $this->erro_campo = "ht16_avaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht16_receitapadrao == null ){ 
       $this->erro_sql = " Campo Receita Padrão nao Informado.";
       $this->erro_campo = "ht16_receitapadrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht16_qtdparcelaspagamento == null ){ 
       $this->erro_sql = " Campo Parcelas Pagamento nao Informado.";
       $this->erro_campo = "ht16_qtdparcelaspagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht16_diaspadraopagamento == null ){ 
       $this->erro_sql = " Campo Dias Pagamento nao Informado.";
       $this->erro_campo = "ht16_diaspadraopagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht16_mesescarencia == null ){ 
       $this->erro_sql = " Campo Meses Carência nao Informado.";
       $this->erro_campo = "ht16_mesescarencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->ht16_anousu == null) || ($this->ht16_anousu == "") ){ 
       $this->erro_sql = " Campo ht16_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitparametro(
                                       ht16_anousu 
                                      ,ht16_avaliacao 
                                      ,ht16_receitapadrao 
                                      ,ht16_qtdparcelaspagamento 
                                      ,ht16_diaspadraopagamento 
                                      ,ht16_mesescarencia 
                       )
                values (
                                $this->ht16_anousu 
                               ,$this->ht16_avaliacao 
                               ,$this->ht16_receitapadrao 
                               ,$this->ht16_qtdparcelaspagamento 
                               ,$this->ht16_diaspadraopagamento 
                               ,$this->ht16_mesescarencia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parâmetros da Habitação ($this->ht16_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parâmetros da Habitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parâmetros da Habitação ($this->ht16_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht16_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht16_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17014,'$this->ht16_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,3013,17014,'','".AddSlashes(pg_result($resaco,0,'ht16_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3013,17015,'','".AddSlashes(pg_result($resaco,0,'ht16_avaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3013,17016,'','".AddSlashes(pg_result($resaco,0,'ht16_receitapadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3013,17017,'','".AddSlashes(pg_result($resaco,0,'ht16_qtdparcelaspagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3013,17018,'','".AddSlashes(pg_result($resaco,0,'ht16_diaspadraopagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3013,17019,'','".AddSlashes(pg_result($resaco,0,'ht16_mesescarencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht16_anousu=null) { 
      $this->atualizacampos();
     $sql = " update habitparametro set ";
     $virgula = "";
     if(trim($this->ht16_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht16_anousu"])){ 
       $sql  .= $virgula." ht16_anousu = $this->ht16_anousu ";
       $virgula = ",";
       if(trim($this->ht16_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "ht16_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht16_avaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht16_avaliacao"])){ 
       $sql  .= $virgula." ht16_avaliacao = $this->ht16_avaliacao ";
       $virgula = ",";
       if(trim($this->ht16_avaliacao) == null ){ 
         $this->erro_sql = " Campo Avaliação nao Informado.";
         $this->erro_campo = "ht16_avaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht16_receitapadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht16_receitapadrao"])){ 
       $sql  .= $virgula." ht16_receitapadrao = $this->ht16_receitapadrao ";
       $virgula = ",";
       if(trim($this->ht16_receitapadrao) == null ){ 
         $this->erro_sql = " Campo Receita Padrão nao Informado.";
         $this->erro_campo = "ht16_receitapadrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht16_qtdparcelaspagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht16_qtdparcelaspagamento"])){ 
       $sql  .= $virgula." ht16_qtdparcelaspagamento = $this->ht16_qtdparcelaspagamento ";
       $virgula = ",";
       if(trim($this->ht16_qtdparcelaspagamento) == null ){ 
         $this->erro_sql = " Campo Parcelas Pagamento nao Informado.";
         $this->erro_campo = "ht16_qtdparcelaspagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht16_diaspadraopagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht16_diaspadraopagamento"])){ 
       $sql  .= $virgula." ht16_diaspadraopagamento = $this->ht16_diaspadraopagamento ";
       $virgula = ",";
       if(trim($this->ht16_diaspadraopagamento) == null ){ 
         $this->erro_sql = " Campo Dias Pagamento nao Informado.";
         $this->erro_campo = "ht16_diaspadraopagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht16_mesescarencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht16_mesescarencia"])){ 
       $sql  .= $virgula." ht16_mesescarencia = $this->ht16_mesescarencia ";
       $virgula = ",";
       if(trim($this->ht16_mesescarencia) == null ){ 
         $this->erro_sql = " Campo Meses Carência nao Informado.";
         $this->erro_campo = "ht16_mesescarencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ht16_anousu!=null){
       $sql .= " ht16_anousu = $this->ht16_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht16_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17014,'$this->ht16_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht16_anousu"]) || $this->ht16_anousu != "")
           $resac = db_query("insert into db_acount values($acount,3013,17014,'".AddSlashes(pg_result($resaco,$conresaco,'ht16_anousu'))."','$this->ht16_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht16_avaliacao"]) || $this->ht16_avaliacao != "")
           $resac = db_query("insert into db_acount values($acount,3013,17015,'".AddSlashes(pg_result($resaco,$conresaco,'ht16_avaliacao'))."','$this->ht16_avaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht16_receitapadrao"]) || $this->ht16_receitapadrao != "")
           $resac = db_query("insert into db_acount values($acount,3013,17016,'".AddSlashes(pg_result($resaco,$conresaco,'ht16_receitapadrao'))."','$this->ht16_receitapadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht16_qtdparcelaspagamento"]) || $this->ht16_qtdparcelaspagamento != "")
           $resac = db_query("insert into db_acount values($acount,3013,17017,'".AddSlashes(pg_result($resaco,$conresaco,'ht16_qtdparcelaspagamento'))."','$this->ht16_qtdparcelaspagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht16_diaspadraopagamento"]) || $this->ht16_diaspadraopagamento != "")
           $resac = db_query("insert into db_acount values($acount,3013,17018,'".AddSlashes(pg_result($resaco,$conresaco,'ht16_diaspadraopagamento'))."','$this->ht16_diaspadraopagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht16_mesescarencia"]) || $this->ht16_mesescarencia != "")
           $resac = db_query("insert into db_acount values($acount,3013,17019,'".AddSlashes(pg_result($resaco,$conresaco,'ht16_mesescarencia'))."','$this->ht16_mesescarencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros da Habitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht16_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros da Habitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht16_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht16_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht16_anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht16_anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17014,'$ht16_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,3013,17014,'','".AddSlashes(pg_result($resaco,$iresaco,'ht16_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3013,17015,'','".AddSlashes(pg_result($resaco,$iresaco,'ht16_avaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3013,17016,'','".AddSlashes(pg_result($resaco,$iresaco,'ht16_receitapadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3013,17017,'','".AddSlashes(pg_result($resaco,$iresaco,'ht16_qtdparcelaspagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3013,17018,'','".AddSlashes(pg_result($resaco,$iresaco,'ht16_diaspadraopagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3013,17019,'','".AddSlashes(pg_result($resaco,$iresaco,'ht16_mesescarencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitparametro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht16_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht16_anousu = $ht16_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros da Habitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht16_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros da Habitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht16_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht16_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitparametro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht16_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitparametro ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = habitparametro.ht16_receitapadrao";
     $sql .= "      inner join avaliacao  on  avaliacao.db101_sequencial = habitparametro.ht16_avaliacao";
     $sql2 = "";
     if($dbwhere==""){
       if($ht16_anousu!=null ){
         $sql2 .= " where habitparametro.ht16_anousu = $ht16_anousu "; 
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
   function sql_query_file ( $ht16_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitparametro ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht16_anousu!=null ){
         $sql2 .= " where habitparametro.ht16_anousu = $ht16_anousu "; 
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