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

//MODULO: agua
//CLASSE DA ENTIDADE agualeitura
class cl_agualeitura { 
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
   var $x21_codleitura = 0; 
   var $x21_codhidrometro = 0; 
   var $x21_exerc = 0; 
   var $x21_mes = 0; 
   var $x21_situacao = 0; 
   var $x21_numcgm = 0; 
   var $x21_dtleitura_dia = null; 
   var $x21_dtleitura_mes = null; 
   var $x21_dtleitura_ano = null; 
   var $x21_dtleitura = null; 
   var $x21_usuario = 0; 
   var $x21_dtinc_dia = null; 
   var $x21_dtinc_mes = null; 
   var $x21_dtinc_ano = null; 
   var $x21_dtinc = null; 
   var $x21_leitura = 0; 
   var $x21_consumo = 0; 
   var $x21_excesso = 0; 
   var $x21_virou = 'f'; 
   var $x21_tipo = 0; 
   var $x21_status = 0; 
   var $x21_saldo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x21_codleitura = int4 = Codigo 
                 x21_codhidrometro = int4 = Hidrômetro 
                 x21_exerc = int4 = Ano 
                 x21_mes = int4 = Mes 
                 x21_situacao = int4 = Situacao 
                 x21_numcgm = int4 = Leiturista 
                 x21_dtleitura = date = Data 
                 x21_usuario = int4 = Usuário 
                 x21_dtinc = date = Data Inclusao 
                 x21_leitura = float8 = Leitura 
                 x21_consumo = float8 = Consumo 
                 x21_excesso = float8 = Excesso 
                 x21_virou = bool = Hidrômetro Virou 
                 x21_tipo = int4 = Tipo de Leitura 
                 x21_status = int4 = Status da Leitura 
                 x21_saldo = float8 = Saldo 
                 ";
   //funcao construtor da classe 
   function cl_agualeitura() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agualeitura"); 
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
       $this->x21_codleitura = ($this->x21_codleitura == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_codleitura"]:$this->x21_codleitura);
       $this->x21_codhidrometro = ($this->x21_codhidrometro == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_codhidrometro"]:$this->x21_codhidrometro);
       $this->x21_exerc = ($this->x21_exerc == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_exerc"]:$this->x21_exerc);
       $this->x21_mes = ($this->x21_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_mes"]:$this->x21_mes);
       $this->x21_situacao = ($this->x21_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_situacao"]:$this->x21_situacao);
       $this->x21_numcgm = ($this->x21_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_numcgm"]:$this->x21_numcgm);
       if($this->x21_dtleitura == ""){
         $this->x21_dtleitura_dia = ($this->x21_dtleitura_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_dtleitura_dia"]:$this->x21_dtleitura_dia);
         $this->x21_dtleitura_mes = ($this->x21_dtleitura_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_dtleitura_mes"]:$this->x21_dtleitura_mes);
         $this->x21_dtleitura_ano = ($this->x21_dtleitura_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_dtleitura_ano"]:$this->x21_dtleitura_ano);
         if($this->x21_dtleitura_dia != ""){
            $this->x21_dtleitura = $this->x21_dtleitura_ano."-".$this->x21_dtleitura_mes."-".$this->x21_dtleitura_dia;
         }
       }
       $this->x21_usuario = ($this->x21_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_usuario"]:$this->x21_usuario);
       if($this->x21_dtinc == ""){
         $this->x21_dtinc_dia = ($this->x21_dtinc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_dtinc_dia"]:$this->x21_dtinc_dia);
         $this->x21_dtinc_mes = ($this->x21_dtinc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_dtinc_mes"]:$this->x21_dtinc_mes);
         $this->x21_dtinc_ano = ($this->x21_dtinc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_dtinc_ano"]:$this->x21_dtinc_ano);
         if($this->x21_dtinc_dia != ""){
            $this->x21_dtinc = $this->x21_dtinc_ano."-".$this->x21_dtinc_mes."-".$this->x21_dtinc_dia;
         }
       }
       $this->x21_leitura = ($this->x21_leitura == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_leitura"]:$this->x21_leitura);
       $this->x21_consumo = ($this->x21_consumo == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_consumo"]:$this->x21_consumo);
       $this->x21_excesso = ($this->x21_excesso == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_excesso"]:$this->x21_excesso);
       $this->x21_virou = ($this->x21_virou == "f"?@$GLOBALS["HTTP_POST_VARS"]["x21_virou"]:$this->x21_virou);
       $this->x21_tipo = ($this->x21_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_tipo"]:$this->x21_tipo);
       $this->x21_status = ($this->x21_status == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_status"]:$this->x21_status);
       $this->x21_saldo = ($this->x21_saldo == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_saldo"]:$this->x21_saldo);
     }else{
       $this->x21_codleitura = ($this->x21_codleitura == ""?@$GLOBALS["HTTP_POST_VARS"]["x21_codleitura"]:$this->x21_codleitura);
     }
   }
   // funcao para inclusao
   function incluir ($x21_codleitura){ 
      $this->atualizacampos();
     if($this->x21_codhidrometro == null ){ 
       $this->erro_sql = " Campo Hidrômetro nao Informado.";
       $this->erro_campo = "x21_codhidrometro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x21_exerc == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "x21_exerc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x21_mes == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "x21_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x21_situacao == null ){ 
       $this->erro_sql = " Campo Situacao nao Informado.";
       $this->erro_campo = "x21_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x21_numcgm == null ){ 
       $this->erro_sql = " Campo Leiturista nao Informado.";
       $this->erro_campo = "x21_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x21_dtleitura == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "x21_dtleitura_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x21_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "x21_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x21_dtinc == null ){ 
       $this->erro_sql = " Campo Data Inclusao nao Informado.";
       $this->erro_campo = "x21_dtinc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x21_leitura == null ){ 
       $this->erro_sql = " Campo Leitura nao Informado.";
       $this->erro_campo = "x21_leitura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x21_consumo == null ){ 
       $this->x21_consumo = "0";
     }
     if($this->x21_excesso == null ){ 
       $this->x21_excesso = "0";
     }
     if($this->x21_virou == null ){ 
       $this->erro_sql = " Campo Hidrômetro Virou nao Informado.";
       $this->erro_campo = "x21_virou";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x21_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Leitura nao Informado.";
       $this->erro_campo = "x21_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x21_status == null ){ 
       $this->erro_sql = " Campo Status da Leitura nao Informado.";
       $this->erro_campo = "x21_status";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x21_saldo == null ){ 
       $this->x21_saldo = "0";
     }
     if($x21_codleitura == "" || $x21_codleitura == null ){
       $result = db_query("select nextval('agualeitura_x21_codleitura_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: agualeitura_x21_codleitura_seq do campo: x21_codleitura"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x21_codleitura = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from agualeitura_x21_codleitura_seq");
       if(($result != false) && (pg_result($result,0,0) < $x21_codleitura)){
         $this->erro_sql = " Campo x21_codleitura maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x21_codleitura = $x21_codleitura; 
       }
     }
     if(($this->x21_codleitura == null) || ($this->x21_codleitura == "") ){ 
       $this->erro_sql = " Campo x21_codleitura nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into agualeitura(
                                       x21_codleitura 
                                      ,x21_codhidrometro 
                                      ,x21_exerc 
                                      ,x21_mes 
                                      ,x21_situacao 
                                      ,x21_numcgm 
                                      ,x21_dtleitura 
                                      ,x21_usuario 
                                      ,x21_dtinc 
                                      ,x21_leitura 
                                      ,x21_consumo 
                                      ,x21_excesso 
                                      ,x21_virou 
                                      ,x21_tipo 
                                      ,x21_status 
                                      ,x21_saldo 
                       )
                values (
                                $this->x21_codleitura 
                               ,$this->x21_codhidrometro 
                               ,$this->x21_exerc 
                               ,$this->x21_mes 
                               ,$this->x21_situacao 
                               ,$this->x21_numcgm 
                               ,".($this->x21_dtleitura == "null" || $this->x21_dtleitura == ""?"null":"'".$this->x21_dtleitura."'")." 
                               ,$this->x21_usuario 
                               ,".($this->x21_dtinc == "null" || $this->x21_dtinc == ""?"null":"'".$this->x21_dtinc."'")." 
                               ,$this->x21_leitura 
                               ,$this->x21_consumo 
                               ,$this->x21_excesso 
                               ,'$this->x21_virou' 
                               ,$this->x21_tipo 
                               ,$this->x21_status 
                               ,$this->x21_saldo 
                      )"; 
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Leitura ($this->x21_codleitura) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Leitura já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Leitura ($this->x21_codleitura) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x21_codleitura;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x21_codleitura));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8469,'$this->x21_codleitura','I')");
       $resac = db_query("insert into db_acount values($acount,1439,8469,'','".AddSlashes(pg_result($resaco,0,'x21_codleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,8470,'','".AddSlashes(pg_result($resaco,0,'x21_codhidrometro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,8471,'','".AddSlashes(pg_result($resaco,0,'x21_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,8472,'','".AddSlashes(pg_result($resaco,0,'x21_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,8473,'','".AddSlashes(pg_result($resaco,0,'x21_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,8474,'','".AddSlashes(pg_result($resaco,0,'x21_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,8475,'','".AddSlashes(pg_result($resaco,0,'x21_dtleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,8476,'','".AddSlashes(pg_result($resaco,0,'x21_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,8477,'','".AddSlashes(pg_result($resaco,0,'x21_dtinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,8478,'','".AddSlashes(pg_result($resaco,0,'x21_leitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,8479,'','".AddSlashes(pg_result($resaco,0,'x21_consumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,8498,'','".AddSlashes(pg_result($resaco,0,'x21_excesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,8801,'','".AddSlashes(pg_result($resaco,0,'x21_virou'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,15437,'','".AddSlashes(pg_result($resaco,0,'x21_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,15438,'','".AddSlashes(pg_result($resaco,0,'x21_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1439,18020,'','".AddSlashes(pg_result($resaco,0,'x21_saldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x21_codleitura=null) { 
      $this->atualizacampos();
     $sql = " update agualeitura set ";
     $virgula = "";
     if(trim($this->x21_codleitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_codleitura"])){ 
       $sql  .= $virgula." x21_codleitura = $this->x21_codleitura ";
       $virgula = ",";
       if(trim($this->x21_codleitura) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "x21_codleitura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x21_codhidrometro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_codhidrometro"])){ 
       $sql  .= $virgula." x21_codhidrometro = $this->x21_codhidrometro ";
       $virgula = ",";
       if(trim($this->x21_codhidrometro) == null ){ 
         $this->erro_sql = " Campo Hidrômetro nao Informado.";
         $this->erro_campo = "x21_codhidrometro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x21_exerc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_exerc"])){ 
       $sql  .= $virgula." x21_exerc = $this->x21_exerc ";
       $virgula = ",";
       if(trim($this->x21_exerc) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "x21_exerc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x21_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_mes"])){ 
       $sql  .= $virgula." x21_mes = $this->x21_mes ";
       $virgula = ",";
       if(trim($this->x21_mes) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "x21_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x21_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_situacao"])){ 
       $sql  .= $virgula." x21_situacao = $this->x21_situacao ";
       $virgula = ",";
       if(trim($this->x21_situacao) == null ){ 
         $this->erro_sql = " Campo Situacao nao Informado.";
         $this->erro_campo = "x21_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x21_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_numcgm"])){ 
       $sql  .= $virgula." x21_numcgm = $this->x21_numcgm ";
       $virgula = ",";
       if(trim($this->x21_numcgm) == null ){ 
         $this->erro_sql = " Campo Leiturista nao Informado.";
         $this->erro_campo = "x21_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x21_dtleitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_dtleitura_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x21_dtleitura_dia"] !="") ){ 
       $sql  .= $virgula." x21_dtleitura = '$this->x21_dtleitura' ";
       $virgula = ",";
       if(trim($this->x21_dtleitura) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "x21_dtleitura_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x21_dtleitura_dia"])){ 
         $sql  .= $virgula." x21_dtleitura = null ";
         $virgula = ",";
         if(trim($this->x21_dtleitura) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "x21_dtleitura_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x21_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_usuario"])){ 
       $sql  .= $virgula." x21_usuario = $this->x21_usuario ";
       $virgula = ",";
       if(trim($this->x21_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "x21_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x21_dtinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_dtinc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x21_dtinc_dia"] !="") ){ 
       $sql  .= $virgula." x21_dtinc = '$this->x21_dtinc' ";
       $virgula = ",";
       if(trim($this->x21_dtinc) == null ){ 
         $this->erro_sql = " Campo Data Inclusao nao Informado.";
         $this->erro_campo = "x21_dtinc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x21_dtinc_dia"])){ 
         $sql  .= $virgula." x21_dtinc = null ";
         $virgula = ",";
         if(trim($this->x21_dtinc) == null ){ 
           $this->erro_sql = " Campo Data Inclusao nao Informado.";
           $this->erro_campo = "x21_dtinc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x21_leitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_leitura"])){ 
       $sql  .= $virgula." x21_leitura = $this->x21_leitura ";
       $virgula = ",";
       if(trim($this->x21_leitura) == null ){ 
         $this->erro_sql = " Campo Leitura nao Informado.";
         $this->erro_campo = "x21_leitura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x21_consumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_consumo"])){ 
        if(trim($this->x21_consumo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x21_consumo"])){ 
           $this->x21_consumo = "0" ; 
        } 
       $sql  .= $virgula." x21_consumo = $this->x21_consumo ";
       $virgula = ",";
     }
     if(trim($this->x21_excesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_excesso"])){ 
        if(trim($this->x21_excesso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x21_excesso"])){ 
           $this->x21_excesso = "0" ; 
        } 
       $sql  .= $virgula." x21_excesso = $this->x21_excesso ";
       $virgula = ",";
     }
     if(trim($this->x21_virou)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_virou"])){ 
       $sql  .= $virgula." x21_virou = '$this->x21_virou' ";
       $virgula = ",";
       if(trim($this->x21_virou) == null ){ 
         $this->erro_sql = " Campo Hidrômetro Virou nao Informado.";
         $this->erro_campo = "x21_virou";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x21_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_tipo"])){ 
       $sql  .= $virgula." x21_tipo = $this->x21_tipo ";
       $virgula = ",";
       if(trim($this->x21_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Leitura nao Informado.";
         $this->erro_campo = "x21_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x21_status)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_status"])){ 
       $sql  .= $virgula." x21_status = $this->x21_status ";
       $virgula = ",";
       if(trim($this->x21_status) == null ){ 
         $this->erro_sql = " Campo Status da Leitura nao Informado.";
         $this->erro_campo = "x21_status";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x21_saldo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x21_saldo"])){ 
        if(trim($this->x21_saldo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x21_saldo"])){ 
           $this->x21_saldo = "0" ; 
        } 
       $sql  .= $virgula." x21_saldo = $this->x21_saldo ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($x21_codleitura!=null){
       $sql .= " x21_codleitura = $this->x21_codleitura";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x21_codleitura));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8469,'$this->x21_codleitura','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_codleitura"]) || $this->x21_codleitura != "")
           $resac = db_query("insert into db_acount values($acount,1439,8469,'".AddSlashes(pg_result($resaco,$conresaco,'x21_codleitura'))."','$this->x21_codleitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_codhidrometro"]) || $this->x21_codhidrometro != "")
           $resac = db_query("insert into db_acount values($acount,1439,8470,'".AddSlashes(pg_result($resaco,$conresaco,'x21_codhidrometro'))."','$this->x21_codhidrometro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_exerc"]) || $this->x21_exerc != "")
           $resac = db_query("insert into db_acount values($acount,1439,8471,'".AddSlashes(pg_result($resaco,$conresaco,'x21_exerc'))."','$this->x21_exerc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_mes"]) || $this->x21_mes != "")
           $resac = db_query("insert into db_acount values($acount,1439,8472,'".AddSlashes(pg_result($resaco,$conresaco,'x21_mes'))."','$this->x21_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_situacao"]) || $this->x21_situacao != "")
           $resac = db_query("insert into db_acount values($acount,1439,8473,'".AddSlashes(pg_result($resaco,$conresaco,'x21_situacao'))."','$this->x21_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_numcgm"]) || $this->x21_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,1439,8474,'".AddSlashes(pg_result($resaco,$conresaco,'x21_numcgm'))."','$this->x21_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_dtleitura"]) || $this->x21_dtleitura != "")
           $resac = db_query("insert into db_acount values($acount,1439,8475,'".AddSlashes(pg_result($resaco,$conresaco,'x21_dtleitura'))."','$this->x21_dtleitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_usuario"]) || $this->x21_usuario != "")
           $resac = db_query("insert into db_acount values($acount,1439,8476,'".AddSlashes(pg_result($resaco,$conresaco,'x21_usuario'))."','$this->x21_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_dtinc"]) || $this->x21_dtinc != "")
           $resac = db_query("insert into db_acount values($acount,1439,8477,'".AddSlashes(pg_result($resaco,$conresaco,'x21_dtinc'))."','$this->x21_dtinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_leitura"]) || $this->x21_leitura != "")
           $resac = db_query("insert into db_acount values($acount,1439,8478,'".AddSlashes(pg_result($resaco,$conresaco,'x21_leitura'))."','$this->x21_leitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_consumo"]) || $this->x21_consumo != "")
           $resac = db_query("insert into db_acount values($acount,1439,8479,'".AddSlashes(pg_result($resaco,$conresaco,'x21_consumo'))."','$this->x21_consumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_excesso"]) || $this->x21_excesso != "")
           $resac = db_query("insert into db_acount values($acount,1439,8498,'".AddSlashes(pg_result($resaco,$conresaco,'x21_excesso'))."','$this->x21_excesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_virou"]) || $this->x21_virou != "")
           $resac = db_query("insert into db_acount values($acount,1439,8801,'".AddSlashes(pg_result($resaco,$conresaco,'x21_virou'))."','$this->x21_virou',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_tipo"]) || $this->x21_tipo != "")
           $resac = db_query("insert into db_acount values($acount,1439,15437,'".AddSlashes(pg_result($resaco,$conresaco,'x21_tipo'))."','$this->x21_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_status"]) || $this->x21_status != "")
           $resac = db_query("insert into db_acount values($acount,1439,15438,'".AddSlashes(pg_result($resaco,$conresaco,'x21_status'))."','$this->x21_status',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x21_saldo"]) || $this->x21_saldo != "")
           $resac = db_query("insert into db_acount values($acount,1439,18020,'".AddSlashes(pg_result($resaco,$conresaco,'x21_saldo'))."','$this->x21_saldo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Leitura nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x21_codleitura;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Leitura nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x21_codleitura;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x21_codleitura;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x21_codleitura=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x21_codleitura));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8469,'$x21_codleitura','E')");
         $resac = db_query("insert into db_acount values($acount,1439,8469,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_codleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,8470,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_codhidrometro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,8471,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,8472,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,8473,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,8474,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,8475,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_dtleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,8476,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,8477,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_dtinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,8478,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_leitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,8479,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_consumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,8498,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_excesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,8801,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_virou'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,15437,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,15438,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1439,18020,'','".AddSlashes(pg_result($resaco,$iresaco,'x21_saldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from agualeitura
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x21_codleitura != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x21_codleitura = $x21_codleitura ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Leitura nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x21_codleitura;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Leitura nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x21_codleitura;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x21_codleitura;
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
        $this->erro_sql   = "Record Vazio na Tabela:agualeitura";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $x21_codleitura=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agualeitura ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = agualeitura.x21_usuario";
     $sql .= "      inner join aguahidromatric  on  aguahidromatric.x04_codhidrometro = agualeitura.x21_codhidrometro";
     $sql .= "      inner join aguasitleitura  on  aguasitleitura.x17_codigo = agualeitura.x21_situacao";
     $sql .= "      inner join agualeiturista  on  agualeiturista.x16_numcgm = agualeitura.x21_numcgm";
     $sql .= "      inner join aguahidromarca  on  aguahidromarca.x03_codmarca = aguahidromatric.x04_codmarca";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguahidromatric.x04_matric";
     $sql .= "      inner join aguahidrodiametro  on  aguahidrodiametro.x15_coddiametro = aguahidromatric.x04_coddiametro";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = agualeiturista.x16_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($x21_codleitura!=null ){
         $sql2 .= " where agualeitura.x21_codleitura = $x21_codleitura "; 
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
   function sql_query_file ( $x21_codleitura=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agualeitura ";
     $sql2 = "";
     if($dbwhere==""){
       if($x21_codleitura!=null ){
         $sql2 .= " where agualeitura.x21_codleitura = $x21_codleitura "; 
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
   function sql_query_sitecgm ( $x21_codleitura=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agualeitura ";
     $sql .= "      inner join aguasitleitura  on  aguasitleitura.x17_codigo = agualeitura.x21_situacao";
     $sql .= "      left  join agualeiturista  on  agualeiturista.x16_numcgm = agualeitura.x21_numcgm";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = agualeiturista.x16_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($x21_codleitura!=null ){
         $sql2 .= " where agualeitura.x21_codleitura = $x21_codleitura "; 
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
   function sql_query_pesquisa ( $x21_codleitura=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agualeitura ";
     $sql .= "      inner join aguahidromatric  on  aguahidromatric.x04_codhidrometro = agualeitura.x21_codhidrometro";
     $sql .= "      inner join aguasitleitura  on  aguasitleitura.x17_codigo = agualeitura.x21_situacao";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguahidromatric.x04_matric";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguabase.x01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($x21_codleitura!=null ){
         $sql2 .= " where agualeitura.x21_codleitura = $x21_codleitura "; 
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
   function sql_query_dados ( $x21_codleitura=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agualeitura ";
     $sql .= "      inner join aguahidromatric  on  aguahidromatric.x04_codhidrometro = agualeitura.x21_codhidrometro";
     $sql .= "      inner join aguasitleitura  on  aguasitleitura.x17_codigo = agualeitura.x21_situacao";
     $sql .= "      left  join agualeiturista  on  agualeiturista.x16_numcgm = agualeitura.x21_numcgm";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = agualeiturista.x16_numcgm";
     $sql .= "      inner join aguahidromarca  on  aguahidromarca.x03_codmarca = aguahidromatric.x04_codmarca";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguahidromatric.x04_matric";
     $sql .= "      inner join aguahidrodiametro  on  aguahidrodiametro.x15_coddiametro = aguahidromatric.x04_coddiametro";
     $sql .= "      inner join cgm a on  a.z01_numcgm = aguabase.x01_numcgm";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguabase.x01_codrua";
     $sql2 = "";
     if($dbwhere==""){
       if($x21_codleitura!=null ){
         $sql2 .= " where agualeitura.x21_codleitura = $x21_codleitura "; 
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
   function sql_query_anteriores ( $x21_codleitura=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agualeitura ";
     $sql .= "      inner join aguahidromatric  on  aguahidromatric.x04_codhidrometro = agualeitura.x21_codhidrometro";
     $sql .= "      inner join aguasitleitura  on  aguasitleitura.x17_codigo = agualeitura.x21_situacao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = agualeitura.x21_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($x21_codleitura!=null ){
         $sql2 .= " where agualeitura.x21_codleitura = $x21_codleitura "; 
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
   function calcula_consumo($iLeitura) {

		if($iLeitura == $this->x21_leitura) {
			return $this->x21_consumo;
		}

		$sql = "select x04_matric from aguahidromatric where x04_codhidrometro = " . $this->x21_codhidrometro;
		$res = db_query($sql);
		db_fieldsmemory($res, 0);



	}
	
	function sql_query_pagamentos_posteriores($iAno, $iMes, $iMatric) {
	  
	  $sql = "select *
              from aguacalc
             inner join arrepaga on arrepaga.k00_numpre = aguacalc.x22_numpre
                                and arrepaga.k00_numpar = aguacalc.x22_mes
             where ((x22_exerc = {$iAno} and x22_mes >= {$iMes})
                or  (x22_exerc > {$iAno} and x22_mes > 0))
               and x22_matric = {$iMatric}
             limit 1";
	  return $sql;
	}
}
?>