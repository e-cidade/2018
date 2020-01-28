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

//MODULO: veiculos
//CLASSE DA ENTIDADE veicabast
class cl_veicabast { 
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
   var $ve70_codigo = 0; 
   var $ve70_veiculos = 0; 
   var $ve70_veiculoscomb = 0; 
   var $ve70_dtabast_dia = null; 
   var $ve70_dtabast_mes = null; 
   var $ve70_dtabast_ano = null; 
   var $ve70_dtabast = null; 
   var $ve70_litros = 0; 
   var $ve70_valor = 0; 
   var $ve70_vlrun = 0; 
   var $ve70_medida = 0; 
   var $ve70_ativo = 0; 
   var $ve70_usuario = 0; 
   var $ve70_data_dia = null; 
   var $ve70_data_mes = null; 
   var $ve70_data_ano = null; 
   var $ve70_data = null; 
   var $ve70_hora = null; 
   var $ve70_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve70_codigo = int4 = Código do Abastecimento 
                 ve70_veiculos = int4 = Veiculo 
                 ve70_veiculoscomb = int4 = Combustível 
                 ve70_dtabast = date = Data do Abastecimento 
                 ve70_litros = float8 = Litros 
                 ve70_valor = float8 = Valor Abastecido 
                 ve70_vlrun = float8 = Valor do Litro 
                 ve70_medida = float8 = Medida de consumo 
                 ve70_ativo = int4 = Ativo 
                 ve70_usuario = int4 = Usuário 
                 ve70_data = date = Data da inclusão do registro 
                 ve70_hora = char(5) = Hora da Inclusão do registro 
                 ve70_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_veicabast() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicabast"); 
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
       $this->ve70_codigo = ($this->ve70_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_codigo"]:$this->ve70_codigo);
       $this->ve70_veiculos = ($this->ve70_veiculos == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_veiculos"]:$this->ve70_veiculos);
       $this->ve70_veiculoscomb = ($this->ve70_veiculoscomb == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_veiculoscomb"]:$this->ve70_veiculoscomb);
       if($this->ve70_dtabast == ""){
         $this->ve70_dtabast_dia = ($this->ve70_dtabast_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_dtabast_dia"]:$this->ve70_dtabast_dia);
         $this->ve70_dtabast_mes = ($this->ve70_dtabast_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_dtabast_mes"]:$this->ve70_dtabast_mes);
         $this->ve70_dtabast_ano = ($this->ve70_dtabast_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_dtabast_ano"]:$this->ve70_dtabast_ano);
         if($this->ve70_dtabast_dia != ""){
            $this->ve70_dtabast = $this->ve70_dtabast_ano."-".$this->ve70_dtabast_mes."-".$this->ve70_dtabast_dia;
         }
       }
       $this->ve70_litros = ($this->ve70_litros == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_litros"]:$this->ve70_litros);
       $this->ve70_valor = ($this->ve70_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_valor"]:$this->ve70_valor);
       $this->ve70_vlrun = ($this->ve70_vlrun == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_vlrun"]:$this->ve70_vlrun);
       $this->ve70_medida = ($this->ve70_medida == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_medida"]:$this->ve70_medida);
       $this->ve70_ativo = ($this->ve70_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_ativo"]:$this->ve70_ativo);
       $this->ve70_usuario = ($this->ve70_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_usuario"]:$this->ve70_usuario);
       if($this->ve70_data == ""){
         $this->ve70_data_dia = ($this->ve70_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_data_dia"]:$this->ve70_data_dia);
         $this->ve70_data_mes = ($this->ve70_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_data_mes"]:$this->ve70_data_mes);
         $this->ve70_data_ano = ($this->ve70_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_data_ano"]:$this->ve70_data_ano);
         if($this->ve70_data_dia != ""){
            $this->ve70_data = $this->ve70_data_ano."-".$this->ve70_data_mes."-".$this->ve70_data_dia;
         }
       }
       $this->ve70_hora = ($this->ve70_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_hora"]:$this->ve70_hora);
       $this->ve70_observacao = ($this->ve70_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_observacao"]:$this->ve70_observacao);
     }else{
       $this->ve70_codigo = ($this->ve70_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve70_codigo"]:$this->ve70_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve70_codigo){ 
      $this->atualizacampos();
     if($this->ve70_veiculos == null ){ 
       $this->erro_sql = " Campo Veiculo nao Informado.";
       $this->erro_campo = "ve70_veiculos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve70_veiculoscomb == null ){ 
       $this->erro_sql = " Campo Combustível nao Informado.";
       $this->erro_campo = "ve70_veiculoscomb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve70_dtabast == null ){ 
       $this->erro_sql = " Campo Data do Abastecimento nao Informado.";
       $this->erro_campo = "ve70_dtabast_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve70_litros == null ){ 
       $this->erro_sql = " Campo Litros nao Informado.";
       $this->erro_campo = "ve70_litros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve70_valor == null ){ 
       $this->ve70_valor = "0";
     }
     if($this->ve70_vlrun == null ){ 
       $this->ve70_vlrun = "0";
     }
     if($this->ve70_medida == null ){ 
       $this->erro_sql = " Campo Medida de consumo nao Informado.";
       $this->erro_campo = "ve70_medida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve70_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "ve70_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve70_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ve70_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve70_data == null ){ 
       $this->erro_sql = " Campo Data da inclusão do registro nao Informado.";
       $this->erro_campo = "ve70_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve70_hora == null ){ 
       $this->erro_sql = " Campo Hora da Inclusão do registro nao Informado.";
       $this->erro_campo = "ve70_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve70_codigo == "" || $ve70_codigo == null ){
       $result = db_query("select nextval('veicabast_ve70_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicabast_ve70_codigo_seq do campo: ve70_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve70_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicabast_ve70_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve70_codigo)){
         $this->erro_sql = " Campo ve70_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve70_codigo = $ve70_codigo; 
       }
     }
     if(($this->ve70_codigo == null) || ($this->ve70_codigo == "") ){ 
       $this->erro_sql = " Campo ve70_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicabast(
                                       ve70_codigo 
                                      ,ve70_veiculos 
                                      ,ve70_veiculoscomb 
                                      ,ve70_dtabast 
                                      ,ve70_litros 
                                      ,ve70_valor 
                                      ,ve70_vlrun 
                                      ,ve70_medida 
                                      ,ve70_ativo 
                                      ,ve70_usuario 
                                      ,ve70_data 
                                      ,ve70_hora 
                                      ,ve70_observacao 
                       )
                values (
                                $this->ve70_codigo 
                               ,$this->ve70_veiculos 
                               ,$this->ve70_veiculoscomb 
                               ,".($this->ve70_dtabast == "null" || $this->ve70_dtabast == ""?"null":"'".$this->ve70_dtabast."'")." 
                               ,$this->ve70_litros 
                               ,$this->ve70_valor 
                               ,$this->ve70_vlrun 
                               ,$this->ve70_medida 
                               ,$this->ve70_ativo 
                               ,$this->ve70_usuario 
                               ,".($this->ve70_data == "null" || $this->ve70_data == ""?"null":"'".$this->ve70_data."'")." 
                               ,'$this->ve70_hora' 
                               ,'$this->ve70_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Abastecimento dos Veículos ($this->ve70_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Abastecimento dos Veículos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Abastecimento dos Veículos ($this->ve70_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve70_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve70_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9365,'$this->ve70_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1610,9365,'','".AddSlashes(pg_result($resaco,0,'ve70_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1610,9366,'','".AddSlashes(pg_result($resaco,0,'ve70_veiculos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1610,9367,'','".AddSlashes(pg_result($resaco,0,'ve70_veiculoscomb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1610,9368,'','".AddSlashes(pg_result($resaco,0,'ve70_dtabast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1610,9369,'','".AddSlashes(pg_result($resaco,0,'ve70_litros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1610,9370,'','".AddSlashes(pg_result($resaco,0,'ve70_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1610,9371,'','".AddSlashes(pg_result($resaco,0,'ve70_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1610,9372,'','".AddSlashes(pg_result($resaco,0,'ve70_medida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1610,9373,'','".AddSlashes(pg_result($resaco,0,'ve70_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1610,9374,'','".AddSlashes(pg_result($resaco,0,'ve70_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1610,9375,'','".AddSlashes(pg_result($resaco,0,'ve70_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1610,9376,'','".AddSlashes(pg_result($resaco,0,'ve70_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1610,18842,'','".AddSlashes(pg_result($resaco,0,'ve70_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve70_codigo=null) { 
      $this->atualizacampos();
     $sql = " update veicabast set ";
     $virgula = "";
     if(trim($this->ve70_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_codigo"])){ 
       $sql  .= $virgula." ve70_codigo = $this->ve70_codigo ";
       $virgula = ",";
       if(trim($this->ve70_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Abastecimento nao Informado.";
         $this->erro_campo = "ve70_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve70_veiculos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_veiculos"])){ 
       $sql  .= $virgula." ve70_veiculos = $this->ve70_veiculos ";
       $virgula = ",";
       if(trim($this->ve70_veiculos) == null ){ 
         $this->erro_sql = " Campo Veiculo nao Informado.";
         $this->erro_campo = "ve70_veiculos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve70_veiculoscomb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_veiculoscomb"])){ 
       $sql  .= $virgula." ve70_veiculoscomb = $this->ve70_veiculoscomb ";
       $virgula = ",";
       if(trim($this->ve70_veiculoscomb) == null ){ 
         $this->erro_sql = " Campo Combustível nao Informado.";
         $this->erro_campo = "ve70_veiculoscomb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve70_dtabast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_dtabast_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve70_dtabast_dia"] !="") ){ 
       $sql  .= $virgula." ve70_dtabast = '$this->ve70_dtabast' ";
       $virgula = ",";
       if(trim($this->ve70_dtabast) == null ){ 
         $this->erro_sql = " Campo Data do Abastecimento nao Informado.";
         $this->erro_campo = "ve70_dtabast_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_dtabast_dia"])){ 
         $sql  .= $virgula." ve70_dtabast = null ";
         $virgula = ",";
         if(trim($this->ve70_dtabast) == null ){ 
           $this->erro_sql = " Campo Data do Abastecimento nao Informado.";
           $this->erro_campo = "ve70_dtabast_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve70_litros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_litros"])){ 
       $sql  .= $virgula." ve70_litros = $this->ve70_litros ";
       $virgula = ",";
       if(trim($this->ve70_litros) == null ){ 
         $this->erro_sql = " Campo Litros nao Informado.";
         $this->erro_campo = "ve70_litros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve70_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_valor"])){ 
        if(trim($this->ve70_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ve70_valor"])){ 
           $this->ve70_valor = "0" ; 
        } 
       $sql  .= $virgula." ve70_valor = $this->ve70_valor ";
       $virgula = ",";
     }
     if(trim($this->ve70_vlrun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_vlrun"])){ 
        if(trim($this->ve70_vlrun)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ve70_vlrun"])){ 
           $this->ve70_vlrun = "0" ; 
        } 
       $sql  .= $virgula." ve70_vlrun = $this->ve70_vlrun ";
       $virgula = ",";
     }
     if(trim($this->ve70_medida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_medida"])){ 
       $sql  .= $virgula." ve70_medida = $this->ve70_medida ";
       $virgula = ",";
       if(trim($this->ve70_medida) == null ){ 
         $this->erro_sql = " Campo Medida de consumo nao Informado.";
         $this->erro_campo = "ve70_medida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve70_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_ativo"])){ 
       $sql  .= $virgula." ve70_ativo = $this->ve70_ativo ";
       $virgula = ",";
       if(trim($this->ve70_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "ve70_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve70_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_usuario"])){ 
       $sql  .= $virgula." ve70_usuario = $this->ve70_usuario ";
       $virgula = ",";
       if(trim($this->ve70_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ve70_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve70_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve70_data_dia"] !="") ){ 
       $sql  .= $virgula." ve70_data = '$this->ve70_data' ";
       $virgula = ",";
       if(trim($this->ve70_data) == null ){ 
         $this->erro_sql = " Campo Data da inclusão do registro nao Informado.";
         $this->erro_campo = "ve70_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_data_dia"])){ 
         $sql  .= $virgula." ve70_data = null ";
         $virgula = ",";
         if(trim($this->ve70_data) == null ){ 
           $this->erro_sql = " Campo Data da inclusão do registro nao Informado.";
           $this->erro_campo = "ve70_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve70_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_hora"])){ 
       $sql  .= $virgula." ve70_hora = '$this->ve70_hora' ";
       $virgula = ",";
       if(trim($this->ve70_hora) == null ){ 
         $this->erro_sql = " Campo Hora da Inclusão do registro nao Informado.";
         $this->erro_campo = "ve70_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve70_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve70_observacao"])){ 
       $sql  .= $virgula." ve70_observacao = '$this->ve70_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ve70_codigo!=null){
       $sql .= " ve70_codigo = $this->ve70_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve70_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9365,'$this->ve70_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_codigo"]) || $this->ve70_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1610,9365,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_codigo'))."','$this->ve70_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_veiculos"]) || $this->ve70_veiculos != "")
           $resac = db_query("insert into db_acount values($acount,1610,9366,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_veiculos'))."','$this->ve70_veiculos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_veiculoscomb"]) || $this->ve70_veiculoscomb != "")
           $resac = db_query("insert into db_acount values($acount,1610,9367,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_veiculoscomb'))."','$this->ve70_veiculoscomb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_dtabast"]) || $this->ve70_dtabast != "")
           $resac = db_query("insert into db_acount values($acount,1610,9368,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_dtabast'))."','$this->ve70_dtabast',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_litros"]) || $this->ve70_litros != "")
           $resac = db_query("insert into db_acount values($acount,1610,9369,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_litros'))."','$this->ve70_litros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_valor"]) || $this->ve70_valor != "")
           $resac = db_query("insert into db_acount values($acount,1610,9370,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_valor'))."','$this->ve70_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_vlrun"]) || $this->ve70_vlrun != "")
           $resac = db_query("insert into db_acount values($acount,1610,9371,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_vlrun'))."','$this->ve70_vlrun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_medida"]) || $this->ve70_medida != "")
           $resac = db_query("insert into db_acount values($acount,1610,9372,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_medida'))."','$this->ve70_medida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_ativo"]) || $this->ve70_ativo != "")
           $resac = db_query("insert into db_acount values($acount,1610,9373,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_ativo'))."','$this->ve70_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_usuario"]) || $this->ve70_usuario != "")
           $resac = db_query("insert into db_acount values($acount,1610,9374,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_usuario'))."','$this->ve70_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_data"]) || $this->ve70_data != "")
           $resac = db_query("insert into db_acount values($acount,1610,9375,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_data'))."','$this->ve70_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_hora"]) || $this->ve70_hora != "")
           $resac = db_query("insert into db_acount values($acount,1610,9376,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_hora'))."','$this->ve70_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve70_observacao"]) || $this->ve70_observacao != "")
           $resac = db_query("insert into db_acount values($acount,1610,18842,'".AddSlashes(pg_result($resaco,$conresaco,'ve70_observacao'))."','$this->ve70_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Abastecimento dos Veículos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve70_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Abastecimento dos Veículos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve70_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve70_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve70_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve70_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9365,'$ve70_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1610,9365,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1610,9366,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_veiculos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1610,9367,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_veiculoscomb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1610,9368,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_dtabast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1610,9369,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_litros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1610,9370,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1610,9371,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1610,9372,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_medida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1610,9373,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1610,9374,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1610,9375,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1610,9376,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1610,18842,'','".AddSlashes(pg_result($resaco,$iresaco,'ve70_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicabast
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve70_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve70_codigo = $ve70_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Abastecimento dos Veículos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve70_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Abastecimento dos Veículos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve70_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve70_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicabast";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ve70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicabast ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = veicabast.ve70_usuario";
     $sql .= "      inner join veiccadcomb  on  veiccadcomb.ve26_codigo = veicabast.ve70_veiculoscomb";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = veicabast.ve70_veiculos";
     $sql .= "      inner join ceplocalidades  on  ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo  on  veiccadtipo.ve20_codigo = veiculos.ve01_veiccadtipo";
     $sql .= "      inner join veiccadmarca  on  veiccadmarca.ve21_codigo = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo  on  veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor  on  veiccadcor.ve23_codigo = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade  on  veiccadtipocapacidade.ve24_codigo = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiccadcategcnh  on  veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced  on  veiccadproced.ve25_codigo = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia  on  veiccadpotencia.ve31_codigo = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg  as a on   a.ve32_codigo = veiculos.ve01_veiccadcateg";
     $sql .= "      inner join veictipoabast  on  veictipoabast.ve07_sequencial = veiculos.ve01_veictipoabast";
     $sql2 = "";
     if($dbwhere==""){
       if($ve70_codigo!=null ){
         $sql2 .= " where veicabast.ve70_codigo = $ve70_codigo "; 
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
   function sql_query_file ( $ve70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicabast ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve70_codigo!=null ){
         $sql2 .= " where veicabast.ve70_codigo = $ve70_codigo "; 
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
   function sql_query_info ( $ve70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from veicabast ";
     $sql .= "      inner join db_usuarios           on db_usuarios.id_usuario              = veicabast.ve70_usuario";
     $sql .= "      inner join veiculoscomb          on veiculoscomb.ve06_veiccadcomb        = veicabast.ve70_veiculoscomb and ve06_veiculos=ve70_veiculos";
     $sql .= "      inner join veiccadcomb           on veiccadcomb.ve26_codigo             = veiculoscomb.ve06_veiccadcomb  ";
     $sql .= "      inner join veiculos              on veiculos.ve01_codigo                = veicabast.ve70_veiculos";
     $sql .= "      inner join ceplocalidades        on ceplocalidades.cp05_codlocalidades  = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo           on veiccadtipo.ve20_codigo             = veiculos.ve01_veiccadtipo";
     $sql .= "      inner join veiccadmarca          on veiccadmarca.ve21_codigo            = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo         on veiccadmodelo.ve22_codigo           = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor            on veiccadcor.ve23_codigo              = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade on veiccadtipocapacidade.ve24_codigo   = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiccadcategcnh       on veiccadcategcnh.ve30_codigo         = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced         on veiccadproced.ve25_codigo           = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia       on veiccadpotencia.ve31_codigo         = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg          on veiccadcateg.ve32_codigo            = veiculos.ve01_veiccadcateg";
     $sql .= "      left  join veicabastretirada     on veicabastretirada.ve73_veicabast    = veicabast.ve70_codigo";
     $sql .= "      left  join veicabastanu          on veicabastanu.ve74_veicabast         = veicabast.ve70_codigo";
     $sql2 = "";

if($dbwhere==""){
       if($ve70_codigo!=null ){
         $sql2 .= " where veicabast.ve70_codigo = $ve70_codigo ";
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
   function sql_query_abastecimento ( $ve70_codigo=null,$campos="*",$ordem=null,$dbwhere="",$agrupar=""){
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

    $sql .= " from veicabast ";
    $sql .= "      inner join db_usuarios       on db_usuarios.id_usuario           = veicabast.ve70_usuario";
    $sql .= "      inner join veiculoscomb      on veiculoscomb.ve06_veiculos       = veicabast.ve70_veiculos and";
    $sql .= "                                      veiculoscomb.ve06_sequencial     = veicabast.ve70_veiculoscomb";
    $sql .= "      inner join veiccadcomb       on veiccadcomb.ve26_codigo          = veiculoscomb.ve06_veiccadcomb";
    $sql .= "      inner join veiculos          on veiculos.ve01_codigo             = veicabast.ve70_veiculos";
    $sql .= "      inner join veicresp          on veicresp.ve02_veiculo            = veicabast.ve70_veiculos";
    $sql .= "      inner join cgm               on cgm.z01_numcgm                   = veicresp.ve02_numcgm";
    $sql .= "      inner join veiccadtipo       on veiccadtipo.ve20_codigo          = veiculos.ve01_veiccadtipo";
    $sql .= "      inner join veiccadmarca      on veiccadmarca.ve21_codigo         = veiculos.ve01_veiccadmarca";
    $sql .= "      inner join veiccadmodelo     on veiccadmodelo.ve22_codigo        = veiculos.ve01_veiccadmodelo";
    $sql .= "      inner join veicabastretirada on veicabastretirada.ve73_veicabast = veicabast.ve70_codigo";
    $sql .= "      inner join veicretirada      on veicretirada.ve60_codigo         = veicabastretirada.ve73_veicretirada";
    $sql .= "      inner join veicdevolucao     on veicdevolucao.ve61_veicretirada  = veicretirada.ve60_codigo";
    $sql .= "      left  join veicabastanu      on veicabastanu.ve74_veicabast      = veicabast.ve70_codigo";
    $sql .= "      left  join veictipoabast     on veictipoabast.ve07_sequencial    = veiculos.ve01_veictipoabast";
    $sql2 = "";
if($dbwhere==""){
      if($ve70_codigo!=null ){
        $sql2 .= " where veicabast.ve70_codigo = $ve70_codigo ";
      }
    }else if(isset($dbwhere) && trim($dbwhere) != ""){
      $sql2 = " where $dbwhere";
    }

    if (isset($agrupar) && trim($agrupar) != ""){
      $sql2 .= " group by ".$agrupar;
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
   function sql_query_posto ( $ve70_codigo=null,$campos="*",$ordem=null,$dbwhere="",$agrupar=""){
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
     $sql .= " from veicabast ";
     $sql .= "      inner join db_usuarios           on db_usuarios.id_usuario = veicabast.ve70_usuario";
     $sql .= "      inner join veiculoscomb          on  veiculoscomb.ve06_veiccadcomb = veicabast.ve70_veiculoscomb  and ve70_veiculos=veiculoscomb.ve06_veiculos";
     $sql .= "      inner join veiccadcomb           on veiccadcomb.ve26_codigo = veiculoscomb.ve06_veiccadcomb";
     $sql .= "      inner join veiculos              on veiculos.ve01_codigo = veicabast.ve70_veiculos";
     $sql .= "      inner join veiccentral           on veiccentral.ve40_veiculos          = veiculos.ve01_codigo";
     $sql .= "      inner join veiccadcentral        on veiccadcentral.ve36_sequencial     = veiccentral.ve40_veiccadcentral";
     $sql .= "      inner join ceplocalidades        on ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo           on veiccadtipo.ve20_codigo = veiculos.ve01_veiccadtipo";
     $sql .= "      inner join veiccadmarca          on veiccadmarca.ve21_codigo = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo         on veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor            on veiccadcor.ve23_codigo = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade on veiccadtipocapacidade.ve24_codigo = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiccadcategcnh       on veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced         on veiccadproced.ve25_codigo = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia       on veiccadpotencia.ve31_codigo = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg  as b    on b.ve32_codigo = veiculos.ve01_veiccadcateg";
     $sql .= "      left  join veicabastposto        on veicabastposto.ve71_veicabast    = veicabast.ve70_codigo";
     $sql .= "      left  join veiccadposto          on veiccadposto.ve29_codigo         = veicabastposto.ve71_veiccadposto";
     $sql .= "      left  join veiccadpostointerno   on veiccadpostointerno.ve35_veiccadposto = veiccadposto.ve29_codigo";
     $sql .= "      left  join veiccadpostoexterno   on veiccadpostoexterno.ve34_veiccadposto = veiccadposto.ve29_codigo";
     $sql .= "      left  join veiccadcentraldepart on veiccadcentraldepart.ve37_veiccadcentral = veiccadcentral.ve36_sequencial";
     $sql2 = "";
if($dbwhere==""){
       if($ve70_codigo!=null ){
         $sql2 .= " where veicabast.ve70_codigo = $ve70_codigo ";
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
   function sql_query_nota ( $ve70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from veicabast ";
     $sql .= " left join veicabastposto        on ve71_veicabast=ve70_codigo ";
     $sql .= " left join veicabastpostoempnota on ve72_veicabastposto=ve71_codigo ";
     $sql .= " left join empnota               on e69_codnota=ve72_empnota ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve70_codigo!=null ){
         $sql2 .= " where veicabast.ve70_codigo = $ve70_codigo ";
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
   function sql_query_file_anula ( $ve70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicabast ";
     $sql .= " left join veicabastanu on veicabastanu.ve74_veicabast=veicabast.ve70_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve70_codigo!=null ){
         $sql2 .= " where veicabast.ve70_codigo = $ve70_codigo ";
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
   function sql_query_abast ( $ve70_codigo=null,$campos="*",$ordem=null,$dbwhere="", $iCoddepto=null){
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
     
     if(!empty($iCoddepto)){
     	$dbwhereSubquery 				 =  " and ve60_coddepto = $iCoddepto ";
     	$dbwhereSubqueryClausula = 'inner';
     }else{
     	$dbwhereSubquery 				 = null;
     	$dbwhereSubqueryClausula = 'left';
     }
     
     $sql .= " 
              ve70_codigo,
              ve70_valor,
              ve01_codigo,
              ve01_placa,
              ve01_veiccadtipo,
              ve01_veiccadmarca,
              ve01_veiccadmodelo, 
              ve01_veictipoabast,
              ve06_veiccadcomb,
              ve20_descr,
              ve21_descr, 
              ve22_descr, 
              ve01_anofab,
              ve01_anomod, 
              ve70_data, 
              ve70_hora, 
              case 
                when coalesce(ve60_medidasaida, ve70_medida) > coalesce(ve70_medida,0) then
                  0
                else
                  ve70_litros
              end as ve70_litros, 
              ve26_descr, 
              ve70_dtabast,
              ve70_veiculoscomb,
              ve07_sigla,
              ve60_destino,
              coalesce(ve60_medidasaida, ve70_medida) as medida_retirada, 
              coalesce(ve70_medida,0) as medida_devolucao, 
              case 
                when coalesce(ve60_medidasaida, ve70_medida) > coalesce(ve70_medida,0) then
                  0
                else
                  coalesce(ve70_medida,0) - coalesce(ve60_medidasaida,0) 
              end as medida_rodada,
              ve40_veiccadcentral,
              descrdepto
              from ( select ve70_codigo,
              							ve70_valor,
                            ve01_codigo,
                            ve01_placa,
                            ve01_veiccadtipo,
                            ve01_veiccadmarca,
                            ve01_veiccadmodelo, 
                            ve01_veictipoabast,
                            ve20_descr,
                            ve21_descr, 
                            ve22_descr, 
                            ve01_anofab,
                            ve01_anomod, 
                            ve70_data, 
                            ve70_hora, 
                            ve70_medida, 
                            ve70_ativo,
                            ve70_litros, 
                            ve26_descr, 
                            ve70_dtabast,
                            ve70_veiculoscomb,
                            ve06_veiccadcomb,
                            descrdepto,
                            (select ve60_destino 
                               from veicretirada 
                              where ve60_codigo = ve73_veicretirada 
                              limit 1 ) as ve60_destino,
                            (select ve07_sigla 
                               from veictipoabast 
                              where ve07_sequencial = ve01_veictipoabast) as ve07_sigla,
                            coalesce( coalesce( (select ve70_medida
                                                        from veicabast a
                                                  where ve70_codigo = (select max(ve70_codigo)
                                                                              from veicabast t
                                                                       where t.ve70_codigo < veicabast.ve70_codigo
                                                                             and t.ve70_veiculos = veicabast.ve70_veiculos
                                                                             limit 1)
                                                 ), ve60_medidasaida ), ve70_medida ) as ve60_medidasaida, 
                            (select ve61_medidadevol 
                               from veicdevolucao 
                                    inner join veicretirada on ve60_codigo = ve61_veicretirada 
                              where ve60_codigo = ve73_veicretirada
                              limit 1 ) as ve61_medidadevol,
                            ve40_veiccadcentral                             
                     from veicabast 
                          $dbwhereSubqueryClausula join veicabastretirada on ve73_veicabast    = ve70_codigo
                          $dbwhereSubqueryClausula join veicretirada      on ve73_veicretirada = ve60_codigo
     																									 $dbwhereSubquery
                          inner join veiculos          on ve01_codigo       = ve70_veiculos 
                          inner join veiccadmarca      on ve21_codigo       = ve01_veiccadmarca 
                          inner join veiccadmodelo     on ve22_codigo       = ve01_veiccadmodelo
                          inner join veiccadtipo       on ve20_codigo       = ve01_veiccadtipo
                          inner join veiculoscomb      on ve06_veiccadcomb  = ve70_veiculoscomb 
                                                      and ve06_veiculos     = ve70_veiculos 
                          inner join veiccadcomb       on ve06_veiccadcomb  = ve26_codigo 
                          left  join veiccentral       on ve40_veiculos     = ve01_codigo
                          left  join veiccadcentral    on ve36_sequencial   = ve40_veiccadcentral
													left  join db_depart         on ve36_coddepto     = coddepto 
                          order by ve01_codigo ) 
              as abast ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve70_codigo!=null ){
         $sql2 .= " where veicabast.ve70_codigo = $ve70_codigo ";
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