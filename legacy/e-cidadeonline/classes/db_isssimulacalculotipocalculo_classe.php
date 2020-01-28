<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: issqn
//CLASSE DA ENTIDADE isssimulacalculotipocalculo
class cl_isssimulacalculotipocalculo { 
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
   var $q132_sequencial = 0; 
   var $q132_isssimulacalculo = 0; 
   var $q132_tipcalc = 0; 
   var $q132_parcela = 0; 
   var $q132_vencimento_dia = null; 
   var $q132_vencimento_mes = null; 
   var $q132_vencimento_ano = null; 
   var $q132_vencimento = null; 
   var $q132_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q132_sequencial = int4 = Sequencial 
                 q132_isssimulacalculo = int4 = Simulação 
                 q132_tipcalc = int4 = Tipo de Calculo 
                 q132_parcela = int4 = Parcela 
                 q132_vencimento = date = Vencimento 
                 q132_valor = float4 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_isssimulacalculotipocalculo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("isssimulacalculotipocalculo"); 
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
       $this->q132_sequencial = ($this->q132_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q132_sequencial"]:$this->q132_sequencial);
       $this->q132_isssimulacalculo = ($this->q132_isssimulacalculo == ""?@$GLOBALS["HTTP_POST_VARS"]["q132_isssimulacalculo"]:$this->q132_isssimulacalculo);
       $this->q132_tipcalc = ($this->q132_tipcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["q132_tipcalc"]:$this->q132_tipcalc);
       $this->q132_parcela = ($this->q132_parcela == ""?@$GLOBALS["HTTP_POST_VARS"]["q132_parcela"]:$this->q132_parcela);
       if($this->q132_vencimento == ""){
         $this->q132_vencimento_dia = ($this->q132_vencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q132_vencimento_dia"]:$this->q132_vencimento_dia);
         $this->q132_vencimento_mes = ($this->q132_vencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q132_vencimento_mes"]:$this->q132_vencimento_mes);
         $this->q132_vencimento_ano = ($this->q132_vencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q132_vencimento_ano"]:$this->q132_vencimento_ano);
         if($this->q132_vencimento_dia != ""){
            $this->q132_vencimento = $this->q132_vencimento_ano."-".$this->q132_vencimento_mes."-".$this->q132_vencimento_dia;
         }
       }
       $this->q132_valor = ($this->q132_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["q132_valor"]:$this->q132_valor);
     }else{
       $this->q132_sequencial = ($this->q132_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q132_sequencial"]:$this->q132_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q132_sequencial){ 
      $this->atualizacampos();
     if($this->q132_isssimulacalculo == null ){ 
       $this->erro_sql = " Campo Simulação nao Informado.";
       $this->erro_campo = "q132_isssimulacalculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q132_tipcalc == null ){ 
       $this->erro_sql = " Campo Tipo de Calculo nao Informado.";
       $this->erro_campo = "q132_tipcalc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q132_parcela == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "q132_parcela";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q132_vencimento == null ){ 
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "q132_vencimento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q132_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "q132_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q132_sequencial == "" || $q132_sequencial == null ){
       $result = db_query("select nextval('isssimulacalculotipocalculo_q132_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: isssimulacalculotipocalculo_q132_sequencial_seq do campo: q132_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q132_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from isssimulacalculotipocalculo_q132_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q132_sequencial)){
         $this->erro_sql = " Campo q132_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q132_sequencial = $q132_sequencial; 
       }
     }
     if(($this->q132_sequencial == null) || ($this->q132_sequencial == "") ){ 
       $this->erro_sql = " Campo q132_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into isssimulacalculotipocalculo(
                                       q132_sequencial 
                                      ,q132_isssimulacalculo 
                                      ,q132_tipcalc 
                                      ,q132_parcela 
                                      ,q132_vencimento 
                                      ,q132_valor 
                       )
                values (
                                $this->q132_sequencial 
                               ,$this->q132_isssimulacalculo 
                               ,$this->q132_tipcalc 
                               ,$this->q132_parcela 
                               ,".($this->q132_vencimento == "null" || $this->q132_vencimento == ""?"null":"'".$this->q132_vencimento."'")." 
                               ,$this->q132_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de Calculo da Simulação ($this->q132_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de Calculo da Simulação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de Calculo da Simulação ($this->q132_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q132_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q132_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18789,'$this->q132_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3332,18789,'','".AddSlashes(pg_result($resaco,0,'q132_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3332,18790,'','".AddSlashes(pg_result($resaco,0,'q132_isssimulacalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3332,18791,'','".AddSlashes(pg_result($resaco,0,'q132_tipcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3332,18792,'','".AddSlashes(pg_result($resaco,0,'q132_parcela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3332,18793,'','".AddSlashes(pg_result($resaco,0,'q132_vencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3332,18794,'','".AddSlashes(pg_result($resaco,0,'q132_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q132_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update isssimulacalculotipocalculo set ";
     $virgula = "";
     if(trim($this->q132_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q132_sequencial"])){ 
       $sql  .= $virgula." q132_sequencial = $this->q132_sequencial ";
       $virgula = ",";
       if(trim($this->q132_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q132_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q132_isssimulacalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q132_isssimulacalculo"])){ 
       $sql  .= $virgula." q132_isssimulacalculo = $this->q132_isssimulacalculo ";
       $virgula = ",";
       if(trim($this->q132_isssimulacalculo) == null ){ 
         $this->erro_sql = " Campo Simulação nao Informado.";
         $this->erro_campo = "q132_isssimulacalculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q132_tipcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q132_tipcalc"])){ 
       $sql  .= $virgula." q132_tipcalc = $this->q132_tipcalc ";
       $virgula = ",";
       if(trim($this->q132_tipcalc) == null ){ 
         $this->erro_sql = " Campo Tipo de Calculo nao Informado.";
         $this->erro_campo = "q132_tipcalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q132_parcela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q132_parcela"])){ 
       $sql  .= $virgula." q132_parcela = $this->q132_parcela ";
       $virgula = ",";
       if(trim($this->q132_parcela) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "q132_parcela";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q132_vencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q132_vencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q132_vencimento_dia"] !="") ){ 
       $sql  .= $virgula." q132_vencimento = '$this->q132_vencimento' ";
       $virgula = ",";
       if(trim($this->q132_vencimento) == null ){ 
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "q132_vencimento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q132_vencimento_dia"])){ 
         $sql  .= $virgula." q132_vencimento = null ";
         $virgula = ",";
         if(trim($this->q132_vencimento) == null ){ 
           $this->erro_sql = " Campo Vencimento nao Informado.";
           $this->erro_campo = "q132_vencimento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q132_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q132_valor"])){ 
       $sql  .= $virgula." q132_valor = $this->q132_valor ";
       $virgula = ",";
       if(trim($this->q132_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "q132_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q132_sequencial!=null){
       $sql .= " q132_sequencial = $this->q132_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q132_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18789,'$this->q132_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q132_sequencial"]) || $this->q132_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3332,18789,'".AddSlashes(pg_result($resaco,$conresaco,'q132_sequencial'))."','$this->q132_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q132_isssimulacalculo"]) || $this->q132_isssimulacalculo != "")
           $resac = db_query("insert into db_acount values($acount,3332,18790,'".AddSlashes(pg_result($resaco,$conresaco,'q132_isssimulacalculo'))."','$this->q132_isssimulacalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q132_tipcalc"]) || $this->q132_tipcalc != "")
           $resac = db_query("insert into db_acount values($acount,3332,18791,'".AddSlashes(pg_result($resaco,$conresaco,'q132_tipcalc'))."','$this->q132_tipcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q132_parcela"]) || $this->q132_parcela != "")
           $resac = db_query("insert into db_acount values($acount,3332,18792,'".AddSlashes(pg_result($resaco,$conresaco,'q132_parcela'))."','$this->q132_parcela',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q132_vencimento"]) || $this->q132_vencimento != "")
           $resac = db_query("insert into db_acount values($acount,3332,18793,'".AddSlashes(pg_result($resaco,$conresaco,'q132_vencimento'))."','$this->q132_vencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q132_valor"]) || $this->q132_valor != "")
           $resac = db_query("insert into db_acount values($acount,3332,18794,'".AddSlashes(pg_result($resaco,$conresaco,'q132_valor'))."','$this->q132_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Calculo da Simulação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q132_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Calculo da Simulação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q132_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q132_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q132_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q132_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18789,'$q132_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3332,18789,'','".AddSlashes(pg_result($resaco,$iresaco,'q132_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3332,18790,'','".AddSlashes(pg_result($resaco,$iresaco,'q132_isssimulacalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3332,18791,'','".AddSlashes(pg_result($resaco,$iresaco,'q132_tipcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3332,18792,'','".AddSlashes(pg_result($resaco,$iresaco,'q132_parcela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3332,18793,'','".AddSlashes(pg_result($resaco,$iresaco,'q132_vencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3332,18794,'','".AddSlashes(pg_result($resaco,$iresaco,'q132_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from isssimulacalculotipocalculo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q132_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q132_sequencial = $q132_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Calculo da Simulação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q132_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Calculo da Simulação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q132_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q132_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:isssimulacalculotipocalculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q132_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isssimulacalculotipocalculo ";
     $sql .= "      inner join tipcalc  on  tipcalc.q81_codigo = isssimulacalculotipocalculo.q132_tipcalc";
     $sql .= "      inner join isssimulacalculo  on  isssimulacalculo.q130_sequencial = isssimulacalculotipocalculo.q132_isssimulacalculo";
     $sql .= "      inner join cadcalc  on  cadcalc.q85_codigo = tipcalc.q81_cadcalc";
     $sql .= "      inner join geradesc  on  geradesc.q89_codigo = tipcalc.q81_gera";
     $sql .= "      inner join bairro  on  bairro.j13_codi = isssimulacalculo.q130_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = isssimulacalculo.q130_logradouro";
     $sql .= "      left  join cadescrito  on  cadescrito.q86_numcgm = isssimulacalculo.q130_cadescrito";
     $sql .= "      inner join zonas  on  zonas.j50_zona = isssimulacalculo.q130_zona";
     $sql2 = "";
     if($dbwhere==""){
       if($q132_sequencial!=null ){
         $sql2 .= " where isssimulacalculotipocalculo.q132_sequencial = $q132_sequencial "; 
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
   function sql_query_file ( $q132_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isssimulacalculotipocalculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($q132_sequencial!=null ){
         $sql2 .= " where isssimulacalculotipocalculo.q132_sequencial = $q132_sequencial "; 
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