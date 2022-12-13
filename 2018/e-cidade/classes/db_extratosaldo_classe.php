<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: Caixa
//CLASSE DA ENTIDADE extratosaldo
class cl_extratosaldo { 
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
   var $k97_sequencial = 0; 
   var $k97_dtsaldofinal_dia = null; 
   var $k97_dtsaldofinal_mes = null; 
   var $k97_dtsaldofinal_ano = null; 
   var $k97_dtsaldofinal = null; 
   var $k97_contabancaria = 0; 
   var $k97_extrato = 0; 
   var $k97_valorcredito = 0; 
   var $k97_valordebito = 0; 
   var $k97_qtdregistros = 0; 
   var $k97_posicao = null; 
   var $k97_situacao = null; 
   var $k97_saldobloqueado = 0; 
   var $k97_saldofinal = 0; 
   var $k97_limite = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k97_sequencial = int4 = Codigo sequencial 
                 k97_dtsaldofinal = date = Data do saldo final 
                 k97_contabancaria = int4 = Codigo sequencial da conta bancaria 
                 k97_extrato = int4 = Codigo sequencial 
                 k97_valorcredito = float8 = Valor a credito 
                 k97_valordebito = float8 = Valor a debito 
                 k97_qtdregistros = int4 = Quantidade de registros 
                 k97_posicao = char(1) = Posicao 
                 k97_situacao = char(1) = Situacao 
                 k97_saldobloqueado = float8 = Saldo bloqueado 
                 k97_saldofinal = float8 = Saldo final 
                 k97_limite = float8 = Limite da conta 
                 ";
   //funcao construtor da classe 
   function cl_extratosaldo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("extratosaldo"); 
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
       $this->k97_sequencial = ($this->k97_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_sequencial"]:$this->k97_sequencial);
       if($this->k97_dtsaldofinal == ""){
         $this->k97_dtsaldofinal_dia = ($this->k97_dtsaldofinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_dtsaldofinal_dia"]:$this->k97_dtsaldofinal_dia);
         $this->k97_dtsaldofinal_mes = ($this->k97_dtsaldofinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_dtsaldofinal_mes"]:$this->k97_dtsaldofinal_mes);
         $this->k97_dtsaldofinal_ano = ($this->k97_dtsaldofinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_dtsaldofinal_ano"]:$this->k97_dtsaldofinal_ano);
         if($this->k97_dtsaldofinal_dia != ""){
            $this->k97_dtsaldofinal = $this->k97_dtsaldofinal_ano."-".$this->k97_dtsaldofinal_mes."-".$this->k97_dtsaldofinal_dia;
         }
       }
       $this->k97_contabancaria = ($this->k97_contabancaria == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_contabancaria"]:$this->k97_contabancaria);
       $this->k97_extrato = ($this->k97_extrato == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_extrato"]:$this->k97_extrato);
       $this->k97_valorcredito = ($this->k97_valorcredito == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_valorcredito"]:$this->k97_valorcredito);
       $this->k97_valordebito = ($this->k97_valordebito == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_valordebito"]:$this->k97_valordebito);
       $this->k97_qtdregistros = ($this->k97_qtdregistros == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_qtdregistros"]:$this->k97_qtdregistros);
       $this->k97_posicao = ($this->k97_posicao == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_posicao"]:$this->k97_posicao);
       $this->k97_situacao = ($this->k97_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_situacao"]:$this->k97_situacao);
       $this->k97_saldobloqueado = ($this->k97_saldobloqueado == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_saldobloqueado"]:$this->k97_saldobloqueado);
       $this->k97_saldofinal = ($this->k97_saldofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_saldofinal"]:$this->k97_saldofinal);
       $this->k97_limite = ($this->k97_limite == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_limite"]:$this->k97_limite);
     }else{
       $this->k97_sequencial = ($this->k97_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k97_sequencial"]:$this->k97_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k97_sequencial){ 
      $this->atualizacampos();
     if($this->k97_dtsaldofinal == null ){ 
       $this->erro_sql = " Campo Data do saldo final nao Informado.";
       $this->erro_campo = "k97_dtsaldofinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k97_contabancaria == null ){ 
       $this->erro_sql = " Campo Codigo sequencial da conta bancaria nao Informado.";
       $this->erro_campo = "k97_contabancaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k97_extrato == null ){ 
       $this->erro_sql = " Campo Codigo sequencial nao Informado.";
       $this->erro_campo = "k97_extrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k97_valorcredito == null ){ 
       $this->k97_valorcredito = "0";
     }
     if($this->k97_valordebito == null ){ 
       $this->k97_valordebito = "0";
     }
     if($this->k97_qtdregistros == null ){ 
       $this->erro_sql = " Campo Quantidade de registros nao Informado.";
       $this->erro_campo = "k97_qtdregistros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k97_posicao == null ){ 
       $this->erro_sql = " Campo Posicao nao Informado.";
       $this->erro_campo = "k97_posicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k97_situacao == null ){ 
       $this->erro_sql = " Campo Situacao nao Informado.";
       $this->erro_campo = "k97_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k97_saldobloqueado == null ){ 
       $this->k97_saldobloqueado = "0";
     }
     if($this->k97_saldofinal == null ){ 
       $this->k97_saldofinal = "0";
     }
     if($this->k97_limite == null ){ 
       $this->k97_limite = "0";
     }
     if($k97_sequencial == "" || $k97_sequencial == null ){
       $result = db_query("select nextval('extratosaldo_k97_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: extratosaldo_k97_sequencial_seq do campo: k97_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k97_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from extratosaldo_k97_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k97_sequencial)){
         $this->erro_sql = " Campo k97_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k97_sequencial = $k97_sequencial; 
       }
     }
     if(($this->k97_sequencial == null) || ($this->k97_sequencial == "") ){ 
       $this->erro_sql = " Campo k97_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into extratosaldo(
                                       k97_sequencial 
                                      ,k97_dtsaldofinal 
                                      ,k97_contabancaria 
                                      ,k97_extrato 
                                      ,k97_valorcredito 
                                      ,k97_valordebito 
                                      ,k97_qtdregistros 
                                      ,k97_posicao 
                                      ,k97_situacao 
                                      ,k97_saldobloqueado 
                                      ,k97_saldofinal 
                                      ,k97_limite 
                       )
                values (
                                $this->k97_sequencial 
                               ,".($this->k97_dtsaldofinal == "null" || $this->k97_dtsaldofinal == ""?"null":"'".$this->k97_dtsaldofinal."'")." 
                               ,$this->k97_contabancaria 
                               ,$this->k97_extrato 
                               ,$this->k97_valorcredito 
                               ,$this->k97_valordebito 
                               ,$this->k97_qtdregistros 
                               ,'$this->k97_posicao' 
                               ,'$this->k97_situacao' 
                               ,$this->k97_saldobloqueado 
                               ,$this->k97_saldofinal 
                               ,$this->k97_limite 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Saldo da conta no extrato por data ($this->k97_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Saldo da conta no extrato por data já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Saldo da conta no extrato por data ($this->k97_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k97_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k97_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10156,'$this->k97_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1749,10156,'','".AddSlashes(pg_result($resaco,0,'k97_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1749,10161,'','".AddSlashes(pg_result($resaco,0,'k97_dtsaldofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1749,15640,'','".AddSlashes(pg_result($resaco,0,'k97_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1749,10158,'','".AddSlashes(pg_result($resaco,0,'k97_extrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1749,10167,'','".AddSlashes(pg_result($resaco,0,'k97_valorcredito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1749,10166,'','".AddSlashes(pg_result($resaco,0,'k97_valordebito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1749,10165,'','".AddSlashes(pg_result($resaco,0,'k97_qtdregistros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1749,10164,'','".AddSlashes(pg_result($resaco,0,'k97_posicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1749,10163,'','".AddSlashes(pg_result($resaco,0,'k97_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1749,10160,'','".AddSlashes(pg_result($resaco,0,'k97_saldobloqueado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1749,10162,'','".AddSlashes(pg_result($resaco,0,'k97_saldofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1749,10159,'','".AddSlashes(pg_result($resaco,0,'k97_limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k97_sequencial=null) { 
     $this->atualizacampos();
     $sql = " update extratosaldo set ";
     $virgula = "";
     if(trim($this->k97_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k97_sequencial"])){ 
       $sql  .= $virgula." k97_sequencial = $this->k97_sequencial ";
       $virgula = ",";
       if(trim($this->k97_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "k97_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k97_dtsaldofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k97_dtsaldofinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k97_dtsaldofinal_dia"] !="") ){ 
       $sql  .= $virgula." k97_dtsaldofinal = '$this->k97_dtsaldofinal' ";
       $virgula = ",";
       if(trim($this->k97_dtsaldofinal) == null ){ 
         $this->erro_sql = " Campo Data do saldo final nao Informado.";
         $this->erro_campo = "k97_dtsaldofinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k97_dtsaldofinal_dia"])){ 
         $sql  .= $virgula." k97_dtsaldofinal = null ";
         $virgula = ",";
         if(trim($this->k97_dtsaldofinal) == null ){ 
           $this->erro_sql = " Campo Data do saldo final nao Informado.";
           $this->erro_campo = "k97_dtsaldofinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k97_contabancaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k97_contabancaria"])){ 
       $sql  .= $virgula." k97_contabancaria = $this->k97_contabancaria ";
       $virgula = ",";
       if(trim($this->k97_contabancaria) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial da conta bancaria nao Informado.";
         $this->erro_campo = "k97_contabancaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k97_extrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k97_extrato"])){ 
       $sql  .= $virgula." k97_extrato = $this->k97_extrato ";
       $virgula = ",";
       if(trim($this->k97_extrato) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "k97_extrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k97_valorcredito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k97_valorcredito"])){ 
        if(trim($this->k97_valorcredito)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k97_valorcredito"])){ 
           $this->k97_valorcredito = "0" ; 
        } 
       $sql  .= $virgula." k97_valorcredito = $this->k97_valorcredito ";
       $virgula = ",";
     }
     if(trim($this->k97_valordebito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k97_valordebito"])){ 
        if(trim($this->k97_valordebito)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k97_valordebito"])){ 
           $this->k97_valordebito = "0" ; 
        } 
       $sql  .= $virgula." k97_valordebito = $this->k97_valordebito ";
       $virgula = ",";
     }
     if(trim($this->k97_qtdregistros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k97_qtdregistros"])){ 
       $sql  .= $virgula." k97_qtdregistros = $this->k97_qtdregistros ";
       $virgula = ",";
       if(trim($this->k97_qtdregistros) == null ){ 
         $this->erro_sql = " Campo Quantidade de registros nao Informado.";
         $this->erro_campo = "k97_qtdregistros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k97_posicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k97_posicao"])){ 
       $sql  .= $virgula." k97_posicao = '$this->k97_posicao' ";
       $virgula = ",";
       if(trim($this->k97_posicao) == null ){ 
         $this->erro_sql = " Campo Posicao nao Informado.";
         $this->erro_campo = "k97_posicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k97_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k97_situacao"])){ 
       $sql  .= $virgula." k97_situacao = '$this->k97_situacao' ";
       $virgula = ",";
       if(trim($this->k97_situacao) == null ){ 
         $this->erro_sql = " Campo Situacao nao Informado.";
         $this->erro_campo = "k97_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k97_saldobloqueado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k97_saldobloqueado"])){ 
        if(trim($this->k97_saldobloqueado)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k97_saldobloqueado"])){ 
           $this->k97_saldobloqueado = "0" ; 
        } 
       $sql  .= $virgula." k97_saldobloqueado = $this->k97_saldobloqueado ";
       $virgula = ",";
     }
     if(trim($this->k97_saldofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k97_saldofinal"])){ 
        if(trim($this->k97_saldofinal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k97_saldofinal"])){ 
           $this->k97_saldofinal = "0" ; 
        } 
       $sql  .= $virgula." k97_saldofinal = $this->k97_saldofinal ";
       $virgula = ",";
     }
     if(trim($this->k97_limite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k97_limite"])){ 
        if(trim($this->k97_limite)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k97_limite"])){ 
           $this->k97_limite = "0" ; 
        } 
       $sql  .= $virgula." k97_limite = $this->k97_limite ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k97_sequencial!=null){
       $sql .= " k97_sequencial = $this->k97_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k97_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10156,'$this->k97_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k97_sequencial"]) || $this->k97_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,1749,10156,'".AddSlashes(pg_result($resaco,$conresaco,'k97_sequencial'))."','$this->k97_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k97_dtsaldofinal"]) || $this->k97_dtsaldofinal != "")
           $resac = db_query("insert into db_acount values($acount,1749,10161,'".AddSlashes(pg_result($resaco,$conresaco,'k97_dtsaldofinal'))."','$this->k97_dtsaldofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k97_contabancaria"]) || $this->k97_contabancaria != "")
           $resac = db_query("insert into db_acount values($acount,1749,15640,'".AddSlashes(pg_result($resaco,$conresaco,'k97_contabancaria'))."','$this->k97_contabancaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k97_extrato"]) || $this->k97_extrato != "")
           $resac = db_query("insert into db_acount values($acount,1749,10158,'".AddSlashes(pg_result($resaco,$conresaco,'k97_extrato'))."','$this->k97_extrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k97_valorcredito"]) || $this->k97_valorcredito != "")
           $resac = db_query("insert into db_acount values($acount,1749,10167,'".AddSlashes(pg_result($resaco,$conresaco,'k97_valorcredito'))."','$this->k97_valorcredito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k97_valordebito"]) || $this->k97_valordebito != "")
           $resac = db_query("insert into db_acount values($acount,1749,10166,'".AddSlashes(pg_result($resaco,$conresaco,'k97_valordebito'))."','$this->k97_valordebito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k97_qtdregistros"]) || $this->k97_qtdregistros != "")
           $resac = db_query("insert into db_acount values($acount,1749,10165,'".AddSlashes(pg_result($resaco,$conresaco,'k97_qtdregistros'))."','$this->k97_qtdregistros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k97_posicao"]) || $this->k97_posicao != "")
           $resac = db_query("insert into db_acount values($acount,1749,10164,'".AddSlashes(pg_result($resaco,$conresaco,'k97_posicao'))."','$this->k97_posicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k97_situacao"]) || $this->k97_situacao != "")
           $resac = db_query("insert into db_acount values($acount,1749,10163,'".AddSlashes(pg_result($resaco,$conresaco,'k97_situacao'))."','$this->k97_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k97_saldobloqueado"]) || $this->k97_saldobloqueado != "")
           $resac = db_query("insert into db_acount values($acount,1749,10160,'".AddSlashes(pg_result($resaco,$conresaco,'k97_saldobloqueado'))."','$this->k97_saldobloqueado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k97_saldofinal"]) || $this->k97_saldofinal != "")
           $resac = db_query("insert into db_acount values($acount,1749,10162,'".AddSlashes(pg_result($resaco,$conresaco,'k97_saldofinal'))."','$this->k97_saldofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k97_limite"]) || $this->k97_limite != "")
           $resac = db_query("insert into db_acount values($acount,1749,10159,'".AddSlashes(pg_result($resaco,$conresaco,'k97_limite'))."','$this->k97_limite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saldo da conta no extrato por data nao Alterado. Alteracao Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->k97_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saldo da conta no extrato por data nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k97_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k97_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k97_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k97_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10156,'$k97_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1749,10156,'','".AddSlashes(pg_result($resaco,$iresaco,'k97_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1749,10161,'','".AddSlashes(pg_result($resaco,$iresaco,'k97_dtsaldofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1749,15640,'','".AddSlashes(pg_result($resaco,$iresaco,'k97_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1749,10158,'','".AddSlashes(pg_result($resaco,$iresaco,'k97_extrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1749,10167,'','".AddSlashes(pg_result($resaco,$iresaco,'k97_valorcredito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1749,10166,'','".AddSlashes(pg_result($resaco,$iresaco,'k97_valordebito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1749,10165,'','".AddSlashes(pg_result($resaco,$iresaco,'k97_qtdregistros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1749,10164,'','".AddSlashes(pg_result($resaco,$iresaco,'k97_posicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1749,10163,'','".AddSlashes(pg_result($resaco,$iresaco,'k97_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1749,10160,'','".AddSlashes(pg_result($resaco,$iresaco,'k97_saldobloqueado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1749,10162,'','".AddSlashes(pg_result($resaco,$iresaco,'k97_saldofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1749,10159,'','".AddSlashes(pg_result($resaco,$iresaco,'k97_limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from extratosaldo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k97_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k97_sequencial = $k97_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saldo da conta no extrato por data nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k97_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saldo da conta no extrato por data nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k97_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k97_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record ($sql) { 
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
        $this->erro_sql   = "Record Vazio na Tabela:extratosaldo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k97_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from extratosaldo ";
     $sql .= "      inner join extrato  on  extrato.k85_sequencial = extratosaldo.k97_extrato";
     $sql .= "      inner join contabancaria  on  contabancaria.db83_sequencial = extratosaldo.k97_contabancaria";
     $sql .= "      inner join bancos  on  bancos.codbco = extrato.k85_codbco";
     $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia";
     $sql2 = "";
     if($dbwhere==""){
       if($k97_sequencial!=null ){
         $sql2 .= " where extratosaldo.k97_sequencial = $k97_sequencial "; 
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
   function sql_query_file ( $k97_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from extratosaldo ";
     $sql2 = "";
     if($dbwhere==""){
       if($k97_sequencial!=null ){
         $sql2 .= " where extratosaldo.k97_sequencial = $k97_sequencial "; 
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

   function recriarSaldo ( $iContabancaria, $dData) {

   	 $rsExtratoSaldo = db_query( $this->sql_query_file(null,
                                                                "k97_sequencial", 
                                                                null, 
                                                                "    k97_dtsaldofinal  = '$dData' 
                                                                 and k97_contabancaria = $iContabancaria") );
     $iSeqExtrato = pg_result($rsExtratoSaldo,0,"k97_sequencial");
        
     $sSqlSaldo = "select k86_data, 
                          k86_contabancaria,
                          sum(valorcredito)               as valorcredito,
                          sum(valordebito)                as valordebito, 
                          sum(valorcredito - valordebito) as saldofinal,
                          ( select k97_saldofinal 
                              from extratosaldo 
                             where k97_contabancaria = {$iContabancaria} 
                               and k97_dtsaldofinal < '{$dData}' 
                             order by k97_dtsaldofinal desc limit 1) as saldoanterior,
                          ( select count(*) 
                              from extratolinha 
                             where k86_data = '{$dData}' 
                               and k86_contabancaria = {$iContabancaria} ) as qtd  
                     from (select k86_tipo, 
                                  k86_data, 
                                  k86_contabancaria, 
                                  case when k86_tipo = 'C' then k86_valor else 0 end as valorcredito,
                                  case when k86_tipo = 'D' then k86_valor else 0 end as valordebito 
                             from extratolinha 
                            where k86_data = '{$dData}' 
                              and k86_contabancaria = {$iContabancaria}
                              and not exists (select 1 
                                                   from conciliapendextrato 
                                                  where k86_sequencial = k88_extratolinha  
                                                    and k88_conciliaorigem = 3 ) ) as x 
                            group by k86_data, 
                                     k86_contabancaria";

     $rsSaldo  = db_query($sSqlSaldo);
     $iNumRows = pg_numrows($rsSaldo); 
     if ($iNumRows > 0) {
     	  $k97_valorcredito = pg_result($rsSaldo,0,"valorcredito");
     	  $k97_valordebito  = pg_result($rsSaldo,0,"valordebito");
        $k97_saldofinal   = pg_result($rsSaldo,0,"saldofinal");
        $k97_saldofinal   = pg_result($rsSaldo,0,"saldoanterior") + $k97_saldofinal;
        $k97_qtdregistros = pg_result($rsSaldo,0,"qtd");
        
        $sSql = "update extratosaldo set k97_valorcredito = {$k97_valorcredito},
                                         k97_valordebito  = {$k97_valordebito}, 
                                         k97_saldofinal   = {$k97_saldofinal},  
                                         k97_qtdregistros = {$k97_qtdregistros} 
                                   where k97_sequencial   = {$iSeqExtrato}";
        $result = db_query($sSql) or die('Erro atualizando extratosaldo'.pg_last_error());
        
     } else {
     	
     	 $sSql = "delete from extratosaldo where k97_sequencial = $iSeqExtrato ";
     	 $result = db_query($sSql) or die('Erro atualizando extratosaldo'.pg_last_error());
     	   
     }
   }  
  
   function recriarSaldoGeral ( $iContabancaria, $dData) {
   	 
     /*
      * 
      * Verificamos se o extrato possui datas posteriores para serem atualizadas.
      * Se existirem elas serão atualizadas juntamente com a conta na data passadas na função
      * 
      */
   	 $sSqlExtratoSaldo = $this->sql_query_file(null,
                                               "k97_sequencial, k97_dtsaldofinal", 
                                               "k97_dtsaldofinal asc", 
                                               "    k97_dtsaldofinal >= '$dData' 
                                                and k97_contabancaria = $iContabancaria");
     $rsExtratoSaldo = db_query($sSqlExtratoSaldo);
     $iNumRowsSaldo = pg_numrows($rsExtratoSaldo);  
     for ( $x = 0; $x < $iNumRowsSaldo; $x++) {

     	  $iSeqExtrato      = pg_result($rsExtratoSaldo,$x,"k97_sequencial");
    	  $dDtSaldoFinal    = pg_result($rsExtratoSaldo,$x,"k97_dtsaldofinal");

   	    $sSqlSaldo = "select k86_data, 
                             k86_contabancaria,
                             sum(valorcredito)               as valorcredito,
                             sum(valordebito)                as valordebito, 
                             coalesce( ( select coalesce(k97_saldofinal,0) as k97_saldofinal 
                                          from extratosaldo 
                                         where k97_contabancaria = {$iContabancaria} 
                                           and k97_dtsaldofinal < '{$dDtSaldoFinal}' 
                                         order by k97_dtsaldofinal desc limit 1), 0) as saldoanterior,
                             ( select count(*) 
                                 from extratolinha 
                                where k86_data = '{$dDtSaldoFinal}' 
                                  and k86_contabancaria = {$iContabancaria} ) as qtd  
                        from (select k86_tipo, 
                                     k86_data, 
                                     k86_contabancaria, 
                                     case when k86_tipo = 'C' then k86_valor else 0 end as valorcredito,
                                     case when k86_tipo = 'D' then k86_valor else 0 end as valordebito 
                                from extratolinha 
                               where k86_data = '{$dDtSaldoFinal}' 
                                 and k86_contabancaria = {$iContabancaria} ) as x 
                               group by k86_data, 
                                        k86_contabancaria";
        $rsSaldo  = db_query($sSqlSaldo);
        $iRows    = pg_numrows($rsSaldo); 
        if ($iRows > 0) {
        	
          $k97_valorcredito = round( pg_result($rsSaldo,0,"valorcredito"),2);
          $k97_valordebito  = round( pg_result($rsSaldo,0,"valordebito") ,2);
          $k97_saldofinal   = $k97_valorcredito - $k97_valordebito;  
          $k97_saldofinal   = round(pg_result($rsSaldo,0,"saldoanterior") + $k97_saldofinal,2);
          $k97_qtdregistros = pg_result($rsSaldo,0,"qtd");
          $sSql = "update extratosaldo set k97_valorcredito = {$k97_valorcredito},
                                           k97_valordebito  = {$k97_valordebito}, 
                                           k97_saldofinal   = {$k97_saldofinal},  
                                           k97_qtdregistros = {$k97_qtdregistros} 
                                     where k97_sequencial   = {$iSeqExtrato}";
          $result = db_query($sSql) or die('Erro atualizando extratosaldo'.pg_last_error());
        
        } else {
          
          $sSql = "delete from extratosaldo where k97_sequencial = $iSeqExtrato ";
          $result = db_query($sSql) or die('Erro atualizando extratosaldo'.pg_last_error());
                  
        }
     }
        
   }
   
}
?>