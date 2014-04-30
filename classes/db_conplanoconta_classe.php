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

//MODULO: Contabilidade
//CLASSE DA ENTIDADE conplanoconta
class cl_conplanoconta { 
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
   var $c63_codcon = 0; 
   var $c63_anousu = 0; 
   var $c63_banco = null; 
   var $c63_agencia = null; 
   var $c63_conta = null; 
   var $c63_dvconta = null; 
   var $c63_dvagencia = null; 
   var $c63_identificador = null; 
   var $c63_codigooperacao = null; 
   var $c63_tipoconta = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c63_codcon = int4 = Reduzido 
                 c63_anousu = int4 = Exercício 
                 c63_banco = varchar(10) = Banco 
                 c63_agencia = varchar(10) = Agência 
                 c63_conta = varchar(50) = Conta Bancária 
                 c63_dvconta = varchar(2) = DV 
                 c63_dvagencia = varchar(2) = DV 
                 c63_identificador = char(14) = Identificador (CNPJ ) 
                 c63_codigooperacao = varchar(4) = Código da Operação 
                 c63_tipoconta = int4 = Tipo da Conta 
                 ";
   //funcao construtor da classe 
   function cl_conplanoconta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conplanoconta"); 
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
       $this->c63_codcon = ($this->c63_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["c63_codcon"]:$this->c63_codcon);
       $this->c63_anousu = ($this->c63_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c63_anousu"]:$this->c63_anousu);
       $this->c63_banco = ($this->c63_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["c63_banco"]:$this->c63_banco);
       $this->c63_agencia = ($this->c63_agencia == ""?@$GLOBALS["HTTP_POST_VARS"]["c63_agencia"]:$this->c63_agencia);
       $this->c63_conta = ($this->c63_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["c63_conta"]:$this->c63_conta);
       $this->c63_dvconta = ($this->c63_dvconta == ""?@$GLOBALS["HTTP_POST_VARS"]["c63_dvconta"]:$this->c63_dvconta);
       $this->c63_dvagencia = ($this->c63_dvagencia == ""?@$GLOBALS["HTTP_POST_VARS"]["c63_dvagencia"]:$this->c63_dvagencia);
       $this->c63_identificador = ($this->c63_identificador == ""?@$GLOBALS["HTTP_POST_VARS"]["c63_identificador"]:$this->c63_identificador);
       $this->c63_codigooperacao = ($this->c63_codigooperacao == ""?@$GLOBALS["HTTP_POST_VARS"]["c63_codigooperacao"]:$this->c63_codigooperacao);
       $this->c63_tipoconta = ($this->c63_tipoconta == ""?@$GLOBALS["HTTP_POST_VARS"]["c63_tipoconta"]:$this->c63_tipoconta);
     }else{
       $this->c63_codcon = ($this->c63_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["c63_codcon"]:$this->c63_codcon);
       $this->c63_anousu = ($this->c63_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c63_anousu"]:$this->c63_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($c63_codcon,$c63_anousu){ 
      $this->atualizacampos();
     if($this->c63_banco == null ){ 
       $this->erro_sql = " Campo Banco nao Informado.";
       $this->erro_campo = "c63_banco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c63_agencia == null ){ 
       $this->erro_sql = " Campo Agência nao Informado.";
       $this->erro_campo = "c63_agencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c63_conta == null ){ 
       $this->erro_sql = " Campo Conta Bancária nao Informado.";
       $this->erro_campo = "c63_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c63_dvconta == null ){ 
       $this->erro_sql = " Campo DV nao Informado.";
       $this->erro_campo = "c63_dvconta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
     if($this->c63_dvagencia == null ){ 
        $this->c63_dvagencia = "";
     }
     
     if($this->c63_tipoconta == null ){ 
       $this->c63_tipoconta = "1";
     }
       $this->c63_codcon = $c63_codcon; 
       $this->c63_anousu = $c63_anousu; 
     if(($this->c63_codcon == null) || ($this->c63_codcon == "") ){ 
       $this->erro_sql = " Campo c63_codcon nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c63_anousu == null) || ($this->c63_anousu == "") ){ 
       $this->erro_sql = " Campo c63_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conplanoconta(
                                       c63_codcon 
                                      ,c63_anousu 
                                      ,c63_banco 
                                      ,c63_agencia 
                                      ,c63_conta 
                                      ,c63_dvconta 
                                      ,c63_dvagencia 
                                      ,c63_identificador 
                                      ,c63_codigooperacao 
                                      ,c63_tipoconta 
                       )
                values (
                                $this->c63_codcon 
                               ,$this->c63_anousu 
                               ,'$this->c63_banco' 
                               ,'$this->c63_agencia' 
                               ,'$this->c63_conta' 
                               ,'$this->c63_dvconta' 
                               ,'$this->c63_dvagencia' 
                               ,'$this->c63_identificador' 
                               ,'$this->c63_codigooperacao' 
                               ,$this->c63_tipoconta 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Banco da Conta do Plano ($this->c63_codcon."-".$this->c63_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Banco da Conta do Plano já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Banco da Conta do Plano ($this->c63_codcon."-".$this->c63_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c63_codcon."-".$this->c63_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c63_codcon,$this->c63_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5239,'$this->c63_codcon','I')");
       $resac = db_query("insert into db_acountkey values($acount,8061,'$this->c63_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,813,5239,'','".AddSlashes(pg_result($resaco,0,'c63_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,813,8061,'','".AddSlashes(pg_result($resaco,0,'c63_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,813,5240,'','".AddSlashes(pg_result($resaco,0,'c63_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,813,5241,'','".AddSlashes(pg_result($resaco,0,'c63_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,813,5242,'','".AddSlashes(pg_result($resaco,0,'c63_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,813,7172,'','".AddSlashes(pg_result($resaco,0,'c63_dvconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,813,7171,'','".AddSlashes(pg_result($resaco,0,'c63_dvagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,813,9829,'','".AddSlashes(pg_result($resaco,0,'c63_identificador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,813,15307,'','".AddSlashes(pg_result($resaco,0,'c63_codigooperacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,813,15308,'','".AddSlashes(pg_result($resaco,0,'c63_tipoconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c63_codcon=null,$c63_anousu=null) { 
      $this->atualizacampos();
     $sql = " update conplanoconta set ";
     $virgula = "";
     if(trim($this->c63_codcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c63_codcon"])){ 
       $sql  .= $virgula." c63_codcon = $this->c63_codcon ";
       $virgula = ",";
       if(trim($this->c63_codcon) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "c63_codcon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c63_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c63_anousu"])){ 
       $sql  .= $virgula." c63_anousu = $this->c63_anousu ";
       $virgula = ",";
       if(trim($this->c63_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "c63_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c63_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c63_banco"])){ 
       $sql  .= $virgula." c63_banco = '$this->c63_banco' ";
       $virgula = ",";
       if(trim($this->c63_banco) == null ){ 
         $this->erro_sql = " Campo Banco nao Informado.";
         $this->erro_campo = "c63_banco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c63_agencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c63_agencia"])){ 
       $sql  .= $virgula." c63_agencia = '$this->c63_agencia' ";
       $virgula = ",";
       if(trim($this->c63_agencia) == null ){ 
         $this->erro_sql = " Campo Agência nao Informado.";
         $this->erro_campo = "c63_agencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c63_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c63_conta"])){ 
       $sql  .= $virgula." c63_conta = '$this->c63_conta' ";
       $virgula = ",";
       if(trim($this->c63_conta) == null ){ 
         $this->erro_sql = " Campo Conta Bancária nao Informado.";
         $this->erro_campo = "c63_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c63_dvconta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c63_dvconta"])){ 
       $sql  .= $virgula." c63_dvconta = '$this->c63_dvconta' ";
       $virgula = ",";
       if(trim($this->c63_dvconta) == null ){ 
         $this->erro_sql = " Campo DV nao Informado.";
         $this->erro_campo = "c63_dvconta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c63_dvagencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c63_dvagencia"])){ 
       $sql  .= $virgula." c63_dvagencia = '$this->c63_dvagencia' ";
       $virgula = ",";
       if(trim($this->c63_dvagencia) == null ){ 
         $this->erro_sql = " Campo DV nao Informado.";
         $this->erro_campo = "c63_dvagencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c63_identificador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c63_identificador"])){ 
       $sql  .= $virgula." c63_identificador = '$this->c63_identificador' ";
       $virgula = ",";
     }
     if(trim($this->c63_codigooperacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c63_codigooperacao"])){ 
       $sql  .= $virgula." c63_codigooperacao = '$this->c63_codigooperacao' ";
       $virgula = ",";
     }
     if(trim($this->c63_tipoconta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c63_tipoconta"])){ 
        if(trim($this->c63_tipoconta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c63_tipoconta"])){ 
           $this->c63_tipoconta = "0" ; 
        } 
       $sql  .= $virgula." c63_tipoconta = $this->c63_tipoconta ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($c63_codcon!=null){
       $sql .= " c63_codcon = $this->c63_codcon";
     }
     if($c63_anousu!=null){
       $sql .= " and  c63_anousu = $this->c63_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c63_codcon,$this->c63_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5239,'$this->c63_codcon','A')");
         $resac = db_query("insert into db_acountkey values($acount,8061,'$this->c63_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c63_codcon"]) || $this->c63_codcon != "")
           $resac = db_query("insert into db_acount values($acount,813,5239,'".AddSlashes(pg_result($resaco,$conresaco,'c63_codcon'))."','$this->c63_codcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c63_anousu"]) || $this->c63_anousu != "")
           $resac = db_query("insert into db_acount values($acount,813,8061,'".AddSlashes(pg_result($resaco,$conresaco,'c63_anousu'))."','$this->c63_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c63_banco"]) || $this->c63_banco != "")
           $resac = db_query("insert into db_acount values($acount,813,5240,'".AddSlashes(pg_result($resaco,$conresaco,'c63_banco'))."','$this->c63_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c63_agencia"]) || $this->c63_agencia != "")
           $resac = db_query("insert into db_acount values($acount,813,5241,'".AddSlashes(pg_result($resaco,$conresaco,'c63_agencia'))."','$this->c63_agencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c63_conta"]) || $this->c63_conta != "")
           $resac = db_query("insert into db_acount values($acount,813,5242,'".AddSlashes(pg_result($resaco,$conresaco,'c63_conta'))."','$this->c63_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c63_dvconta"]) || $this->c63_dvconta != "")
           $resac = db_query("insert into db_acount values($acount,813,7172,'".AddSlashes(pg_result($resaco,$conresaco,'c63_dvconta'))."','$this->c63_dvconta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c63_dvagencia"]) || $this->c63_dvagencia != "")
           $resac = db_query("insert into db_acount values($acount,813,7171,'".AddSlashes(pg_result($resaco,$conresaco,'c63_dvagencia'))."','$this->c63_dvagencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c63_identificador"]) || $this->c63_identificador != "")
           $resac = db_query("insert into db_acount values($acount,813,9829,'".AddSlashes(pg_result($resaco,$conresaco,'c63_identificador'))."','$this->c63_identificador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c63_codigooperacao"]) || $this->c63_codigooperacao != "")
           $resac = db_query("insert into db_acount values($acount,813,15307,'".AddSlashes(pg_result($resaco,$conresaco,'c63_codigooperacao'))."','$this->c63_codigooperacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c63_tipoconta"]) || $this->c63_tipoconta != "")
           $resac = db_query("insert into db_acount values($acount,813,15308,'".AddSlashes(pg_result($resaco,$conresaco,'c63_tipoconta'))."','$this->c63_tipoconta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Banco da Conta do Plano nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c63_codcon."-".$this->c63_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Banco da Conta do Plano nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c63_codcon."-".$this->c63_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c63_codcon."-".$this->c63_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c63_codcon=null,$c63_anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c63_codcon,$c63_anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5239,'$c63_codcon','E')");
         $resac = db_query("insert into db_acountkey values($acount,8061,'$c63_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,813,5239,'','".AddSlashes(pg_result($resaco,$iresaco,'c63_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,813,8061,'','".AddSlashes(pg_result($resaco,$iresaco,'c63_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,813,5240,'','".AddSlashes(pg_result($resaco,$iresaco,'c63_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,813,5241,'','".AddSlashes(pg_result($resaco,$iresaco,'c63_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,813,5242,'','".AddSlashes(pg_result($resaco,$iresaco,'c63_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,813,7172,'','".AddSlashes(pg_result($resaco,$iresaco,'c63_dvconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,813,7171,'','".AddSlashes(pg_result($resaco,$iresaco,'c63_dvagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,813,9829,'','".AddSlashes(pg_result($resaco,$iresaco,'c63_identificador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,813,15307,'','".AddSlashes(pg_result($resaco,$iresaco,'c63_codigooperacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,813,15308,'','".AddSlashes(pg_result($resaco,$iresaco,'c63_tipoconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conplanoconta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c63_codcon != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c63_codcon = $c63_codcon ";
        }
        if($c63_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c63_anousu = $c63_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Banco da Conta do Plano nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c63_codcon."-".$c63_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Banco da Conta do Plano nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c63_codcon."-".$c63_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c63_codcon."-".$c63_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:conplanoconta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c63_codcon=null,$c63_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conplanoconta ";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = conplanoconta.c63_codcon and  conplano.c60_anousu = conplanoconta.c63_anousu";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = conplanoconta.c63_banco";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql2 = "";
     if($dbwhere==""){
       if($c63_codcon!=null ){
         $sql2 .= " where conplanoconta.c63_codcon = $c63_codcon "; 
       } 
       if($c63_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conplanoconta.c63_anousu = $c63_anousu "; 
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
   function sql_query_file ( $c63_codcon=null,$c63_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conplanoconta ";
     $sql2 = "";
     if($dbwhere==""){
       if($c63_codcon!=null ){
         $sql2 .= " where conplanoconta.c63_codcon = $c63_codcon "; 
       } 
       if($c63_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conplanoconta.c63_anousu = $c63_anousu "; 
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
   function sql_query_razao ( $c63_codcon=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conplanoconta ";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = conplanoconta.c63_codcon";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join conplanoreduz on  conplanoreduz.c61_codcon = conplanoconta.c63_codcon";
     $sql .= "      inner join conlancamval on  conlancamval.c69_credito=conplanoreduz.c61_reduz or conlancamval.c69_debito=conplanoreduz.c61_reduz";
     $sql .= "      inner join conlancam on conlancam.c70_codlan = conlancamval.c69_codlan and conlancam.c70_anousu = conlancamval.c69_anousu  ";
     $sql2 = "";
     if($dbwhere==""){
       if($c63_codcon!=null ){
         $sql2 .= " where conplanoconta.c63_codcon = $c63_codcon ";
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