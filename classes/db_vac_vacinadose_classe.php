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

//MODULO: vacinas
//CLASSE DA ENTIDADE vac_vacinadose
class cl_vac_vacinadose { 
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
   var $vc07_i_codigo = 0; 
   var $vc07_i_diasvalidade = 0; 
   var $vc07_i_vacina = 0; 
   var $vc07_i_dose = 0; 
   var $vc07_i_calendario = 0; 
   var $vc07_c_nome = null; 
   var $vc07_n_quant = 0; 
   var $vc07_i_diasantecipacao = 0; 
   var $vc07_c_descr = null; 
   var $vc07_i_situacao = 0; 
   var $vc07_i_sexo = 0; 
   var $vc07_i_tipocalculo = 0; 
   var $vc07_i_diasatraso = 0; 
   var $vc07_i_faixainidias = 0; 
   var $vc07_i_faixainimes = 0; 
   var $vc07_i_faixainiano = 0; 
   var $vc07_i_faixafimdias = 0; 
   var $vc07_i_faixafimmes = 0; 
   var $vc07_i_faixafimano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc07_i_codigo = int4 = Código 
                 vc07_i_diasvalidade = int4 = Validade em dias 
                 vc07_i_vacina = int4 = Vacina 
                 vc07_i_dose = int4 = Dose 
                 vc07_i_calendario = int4 = Calendário 
                 vc07_c_nome = char(50) = Nome 
                 vc07_n_quant = float4 = Quantidade 
                 vc07_i_diasantecipacao = int4 = Tol. de Antecip. em Dias 
                 vc07_c_descr = char(100) = Descrição 
                 vc07_i_situacao = int4 = Situação 
                 vc07_i_sexo = int4 = Sexo 
                 vc07_i_tipocalculo = int4 = Tipo de Calculo 
                 vc07_i_diasatraso = int4 = Tol. de Atraso em Dias 
                 vc07_i_faixainidias = int4 = Faixa dia inicial 
                 vc07_i_faixainimes = int4 = Faixa mês inicial 
                 vc07_i_faixainiano = int4 = Faixa ano inicial 
                 vc07_i_faixafimdias = int4 = Faixa dia final 
                 vc07_i_faixafimmes = int4 = Faixa mês final 
                 vc07_i_faixafimano = int4 = Faixa ano final 
                 ";
   //funcao construtor da classe 
   function cl_vac_vacinadose() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_vacinadose"); 
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
       $this->vc07_i_codigo = ($this->vc07_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_codigo"]:$this->vc07_i_codigo);
       $this->vc07_i_diasvalidade = ($this->vc07_i_diasvalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_diasvalidade"]:$this->vc07_i_diasvalidade);
       $this->vc07_i_vacina = ($this->vc07_i_vacina == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_vacina"]:$this->vc07_i_vacina);
       $this->vc07_i_dose = ($this->vc07_i_dose == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_dose"]:$this->vc07_i_dose);
       $this->vc07_i_calendario = ($this->vc07_i_calendario == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_calendario"]:$this->vc07_i_calendario);
       $this->vc07_c_nome = ($this->vc07_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_c_nome"]:$this->vc07_c_nome);
       $this->vc07_n_quant = ($this->vc07_n_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_n_quant"]:$this->vc07_n_quant);
       $this->vc07_i_diasantecipacao = ($this->vc07_i_diasantecipacao == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_diasantecipacao"]:$this->vc07_i_diasantecipacao);
       $this->vc07_c_descr = ($this->vc07_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_c_descr"]:$this->vc07_c_descr);
       $this->vc07_i_situacao = ($this->vc07_i_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_situacao"]:$this->vc07_i_situacao);
       $this->vc07_i_sexo = ($this->vc07_i_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_sexo"]:$this->vc07_i_sexo);
       $this->vc07_i_tipocalculo = ($this->vc07_i_tipocalculo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_tipocalculo"]:$this->vc07_i_tipocalculo);
       $this->vc07_i_diasatraso = ($this->vc07_i_diasatraso == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_diasatraso"]:$this->vc07_i_diasatraso);
       $this->vc07_i_faixainidias = ($this->vc07_i_faixainidias == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_faixainidias"]:$this->vc07_i_faixainidias);
       $this->vc07_i_faixainimes = ($this->vc07_i_faixainimes == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_faixainimes"]:$this->vc07_i_faixainimes);
       $this->vc07_i_faixainiano = ($this->vc07_i_faixainiano == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_faixainiano"]:$this->vc07_i_faixainiano);
       $this->vc07_i_faixafimdias = ($this->vc07_i_faixafimdias == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_faixafimdias"]:$this->vc07_i_faixafimdias);
       $this->vc07_i_faixafimmes = ($this->vc07_i_faixafimmes == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_faixafimmes"]:$this->vc07_i_faixafimmes);
       $this->vc07_i_faixafimano = ($this->vc07_i_faixafimano == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_faixafimano"]:$this->vc07_i_faixafimano);
     }else{
       $this->vc07_i_codigo = ($this->vc07_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc07_i_codigo"]:$this->vc07_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc07_i_codigo){ 
      $this->atualizacampos();
     if($this->vc07_i_diasvalidade == null ){ 
       $this->vc07_i_diasvalidade = "0";
     }
     if($this->vc07_i_vacina == null ){ 
       $this->erro_sql = " Campo Vacina nao Informado.";
       $this->erro_campo = "vc07_i_vacina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc07_i_dose == null ){ 
       $this->erro_sql = " Campo Dose nao Informado.";
       $this->erro_campo = "vc07_i_dose";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc07_i_calendario == null ){ 
       $this->erro_sql = " Campo Calendário nao Informado.";
       $this->erro_campo = "vc07_i_calendario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc07_c_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "vc07_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc07_n_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "vc07_n_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc07_i_diasantecipacao == null ){ 
       $this->vc07_i_diasantecipacao = "0";
     }
     if($this->vc07_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "vc07_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc07_i_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "vc07_i_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc07_i_sexo == null ){ 
       $this->vc07_i_sexo = "0";
     }
     if($this->vc07_i_tipocalculo == null ){ 
       $this->vc07_i_tipocalculo = "0";
     }
     if($this->vc07_i_diasatraso == null ){ 
       $this->vc07_i_diasatraso = "0";
     }
     if($this->vc07_i_faixainidias == null ){ 
       $this->vc07_i_faixainidias = "0";
     }
     if($this->vc07_i_faixainimes == null ){ 
       $this->vc07_i_faixainimes = "0";
     }
     if($this->vc07_i_faixainiano == null ){ 
       $this->vc07_i_faixainiano = "0";
     }
     if($this->vc07_i_faixafimdias == null ){ 
       $this->vc07_i_faixafimdias = "0";
     }
     if($this->vc07_i_faixafimmes == null ){ 
       $this->vc07_i_faixafimmes = "0";
     }
     if($this->vc07_i_faixafimano == null ){ 
       $this->vc07_i_faixafimano = "0";
     }
     if($vc07_i_codigo == "" || $vc07_i_codigo == null ){
       $result = db_query("select nextval('vac_vacinadose_vc07_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_vacinadose_vc07_i_codigo_seq do campo: vc07_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc07_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_vacinadose_vc07_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc07_i_codigo)){
         $this->erro_sql = " Campo vc07_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc07_i_codigo = $vc07_i_codigo; 
       }
     }
     if(($this->vc07_i_codigo == null) || ($this->vc07_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc07_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_vacinadose(
                                       vc07_i_codigo 
                                      ,vc07_i_diasvalidade 
                                      ,vc07_i_vacina 
                                      ,vc07_i_dose 
                                      ,vc07_i_calendario 
                                      ,vc07_c_nome 
                                      ,vc07_n_quant 
                                      ,vc07_i_diasantecipacao 
                                      ,vc07_c_descr 
                                      ,vc07_i_situacao 
                                      ,vc07_i_sexo 
                                      ,vc07_i_tipocalculo 
                                      ,vc07_i_diasatraso 
                                      ,vc07_i_faixainidias 
                                      ,vc07_i_faixainimes 
                                      ,vc07_i_faixainiano 
                                      ,vc07_i_faixafimdias 
                                      ,vc07_i_faixafimmes 
                                      ,vc07_i_faixafimano 
                       )
                values (
                                $this->vc07_i_codigo 
                               ,$this->vc07_i_diasvalidade 
                               ,$this->vc07_i_vacina 
                               ,$this->vc07_i_dose 
                               ,$this->vc07_i_calendario 
                               ,'$this->vc07_c_nome' 
                               ,$this->vc07_n_quant 
                               ,$this->vc07_i_diasantecipacao 
                               ,'$this->vc07_c_descr' 
                               ,$this->vc07_i_situacao 
                               ,$this->vc07_i_sexo 
                               ,$this->vc07_i_tipocalculo 
                               ,$this->vc07_i_diasatraso 
                               ,$this->vc07_i_faixainidias 
                               ,$this->vc07_i_faixainimes 
                               ,$this->vc07_i_faixainiano 
                               ,$this->vc07_i_faixafimdias 
                               ,$this->vc07_i_faixafimmes 
                               ,$this->vc07_i_faixafimano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vacina Dose ($this->vc07_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vacina Dose já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vacina Dose ($this->vc07_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc07_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc07_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16814,'$this->vc07_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2961,16814,'','".AddSlashes(pg_result($resaco,0,'vc07_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16815,'','".AddSlashes(pg_result($resaco,0,'vc07_i_diasvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16816,'','".AddSlashes(pg_result($resaco,0,'vc07_i_vacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16817,'','".AddSlashes(pg_result($resaco,0,'vc07_i_dose'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16818,'','".AddSlashes(pg_result($resaco,0,'vc07_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16819,'','".AddSlashes(pg_result($resaco,0,'vc07_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16821,'','".AddSlashes(pg_result($resaco,0,'vc07_n_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16827,'','".AddSlashes(pg_result($resaco,0,'vc07_i_diasantecipacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16820,'','".AddSlashes(pg_result($resaco,0,'vc07_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16822,'','".AddSlashes(pg_result($resaco,0,'vc07_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16823,'','".AddSlashes(pg_result($resaco,0,'vc07_i_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16824,'','".AddSlashes(pg_result($resaco,0,'vc07_i_tipocalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16826,'','".AddSlashes(pg_result($resaco,0,'vc07_i_diasatraso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16828,'','".AddSlashes(pg_result($resaco,0,'vc07_i_faixainidias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16829,'','".AddSlashes(pg_result($resaco,0,'vc07_i_faixainimes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16830,'','".AddSlashes(pg_result($resaco,0,'vc07_i_faixainiano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16831,'','".AddSlashes(pg_result($resaco,0,'vc07_i_faixafimdias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16832,'','".AddSlashes(pg_result($resaco,0,'vc07_i_faixafimmes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2961,16833,'','".AddSlashes(pg_result($resaco,0,'vc07_i_faixafimano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc07_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_vacinadose set ";
     $virgula = "";
     if(trim($this->vc07_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_codigo"])){ 
       $sql  .= $virgula." vc07_i_codigo = $this->vc07_i_codigo ";
       $virgula = ",";
       if(trim($this->vc07_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "vc07_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc07_i_diasvalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_diasvalidade"])){ 
        if(trim($this->vc07_i_diasvalidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_diasvalidade"])){ 
           $this->vc07_i_diasvalidade = "0" ; 
        } 
       $sql  .= $virgula." vc07_i_diasvalidade = $this->vc07_i_diasvalidade ";
       $virgula = ",";
     }
     if(trim($this->vc07_i_vacina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_vacina"])){ 
       $sql  .= $virgula." vc07_i_vacina = $this->vc07_i_vacina ";
       $virgula = ",";
       if(trim($this->vc07_i_vacina) == null ){ 
         $this->erro_sql = " Campo Vacina nao Informado.";
         $this->erro_campo = "vc07_i_vacina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc07_i_dose)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_dose"])){ 
       $sql  .= $virgula." vc07_i_dose = $this->vc07_i_dose ";
       $virgula = ",";
       if(trim($this->vc07_i_dose) == null ){ 
         $this->erro_sql = " Campo Dose nao Informado.";
         $this->erro_campo = "vc07_i_dose";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc07_i_calendario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_calendario"])){ 
       $sql  .= $virgula." vc07_i_calendario = $this->vc07_i_calendario ";
       $virgula = ",";
       if(trim($this->vc07_i_calendario) == null ){ 
         $this->erro_sql = " Campo Calendário nao Informado.";
         $this->erro_campo = "vc07_i_calendario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc07_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_c_nome"])){ 
       $sql  .= $virgula." vc07_c_nome = '$this->vc07_c_nome' ";
       $virgula = ",";
       if(trim($this->vc07_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "vc07_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc07_n_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_n_quant"])){ 
       $sql  .= $virgula." vc07_n_quant = $this->vc07_n_quant ";
       $virgula = ",";
       if(trim($this->vc07_n_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "vc07_n_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc07_i_diasantecipacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_diasantecipacao"])){ 
        if(trim($this->vc07_i_diasantecipacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_diasantecipacao"])){ 
           $this->vc07_i_diasantecipacao = "0" ; 
        } 
       $sql  .= $virgula." vc07_i_diasantecipacao = $this->vc07_i_diasantecipacao ";
       $virgula = ",";
     }
     if(trim($this->vc07_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_c_descr"])){ 
       $sql  .= $virgula." vc07_c_descr = '$this->vc07_c_descr' ";
       $virgula = ",";
       if(trim($this->vc07_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "vc07_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc07_i_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_situacao"])){ 
       $sql  .= $virgula." vc07_i_situacao = $this->vc07_i_situacao ";
       $virgula = ",";
       if(trim($this->vc07_i_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "vc07_i_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc07_i_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_sexo"])){ 
        if(trim($this->vc07_i_sexo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_sexo"])){ 
           $this->vc07_i_sexo = "0" ; 
        } 
       $sql  .= $virgula." vc07_i_sexo = $this->vc07_i_sexo ";
       $virgula = ",";
     }
     if(trim($this->vc07_i_tipocalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_tipocalculo"])){ 
        if(trim($this->vc07_i_tipocalculo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_tipocalculo"])){ 
           $this->vc07_i_tipocalculo = "0" ; 
        } 
       $sql  .= $virgula." vc07_i_tipocalculo = $this->vc07_i_tipocalculo ";
       $virgula = ",";
     }
     if(trim($this->vc07_i_diasatraso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_diasatraso"])){ 
        if(trim($this->vc07_i_diasatraso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_diasatraso"])){ 
           $this->vc07_i_diasatraso = "0" ; 
        } 
       $sql  .= $virgula." vc07_i_diasatraso = $this->vc07_i_diasatraso ";
       $virgula = ",";
     }
     if(trim($this->vc07_i_faixainidias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixainidias"])){ 
        if(trim($this->vc07_i_faixainidias)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixainidias"])){ 
           $this->vc07_i_faixainidias = "0" ; 
        } 
       $sql  .= $virgula." vc07_i_faixainidias = $this->vc07_i_faixainidias ";
       $virgula = ",";
     }
     if(trim($this->vc07_i_faixainimes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixainimes"])){ 
        if(trim($this->vc07_i_faixainimes)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixainimes"])){ 
           $this->vc07_i_faixainimes = "0" ; 
        } 
       $sql  .= $virgula." vc07_i_faixainimes = $this->vc07_i_faixainimes ";
       $virgula = ",";
     }
     if(trim($this->vc07_i_faixainiano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixainiano"])){ 
        if(trim($this->vc07_i_faixainiano)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixainiano"])){ 
           $this->vc07_i_faixainiano = "0" ; 
        } 
       $sql  .= $virgula." vc07_i_faixainiano = $this->vc07_i_faixainiano ";
       $virgula = ",";
     }
     if(trim($this->vc07_i_faixafimdias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixafimdias"])){ 
        if(trim($this->vc07_i_faixafimdias)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixafimdias"])){ 
           $this->vc07_i_faixafimdias = "0" ; 
        } 
       $sql  .= $virgula." vc07_i_faixafimdias = $this->vc07_i_faixafimdias ";
       $virgula = ",";
     }
     if(trim($this->vc07_i_faixafimmes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixafimmes"])){ 
        if(trim($this->vc07_i_faixafimmes)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixafimmes"])){ 
           $this->vc07_i_faixafimmes = "0" ; 
        } 
       $sql  .= $virgula." vc07_i_faixafimmes = $this->vc07_i_faixafimmes ";
       $virgula = ",";
     }
     if(trim($this->vc07_i_faixafimano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixafimano"])){ 
        if(trim($this->vc07_i_faixafimano)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixafimano"])){ 
           $this->vc07_i_faixafimano = "0" ; 
        } 
       $sql  .= $virgula." vc07_i_faixafimano = $this->vc07_i_faixafimano ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($vc07_i_codigo!=null){
       $sql .= " vc07_i_codigo = $this->vc07_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc07_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16814,'$this->vc07_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_codigo"]) || $this->vc07_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2961,16814,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_codigo'))."','$this->vc07_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_diasvalidade"]) || $this->vc07_i_diasvalidade != "")
           $resac = db_query("insert into db_acount values($acount,2961,16815,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_diasvalidade'))."','$this->vc07_i_diasvalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_vacina"]) || $this->vc07_i_vacina != "")
           $resac = db_query("insert into db_acount values($acount,2961,16816,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_vacina'))."','$this->vc07_i_vacina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_dose"]) || $this->vc07_i_dose != "")
           $resac = db_query("insert into db_acount values($acount,2961,16817,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_dose'))."','$this->vc07_i_dose',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_calendario"]) || $this->vc07_i_calendario != "")
           $resac = db_query("insert into db_acount values($acount,2961,16818,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_calendario'))."','$this->vc07_i_calendario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_c_nome"]) || $this->vc07_c_nome != "")
           $resac = db_query("insert into db_acount values($acount,2961,16819,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_c_nome'))."','$this->vc07_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_n_quant"]) || $this->vc07_n_quant != "")
           $resac = db_query("insert into db_acount values($acount,2961,16821,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_n_quant'))."','$this->vc07_n_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_diasantecipacao"]) || $this->vc07_i_diasantecipacao != "")
           $resac = db_query("insert into db_acount values($acount,2961,16827,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_diasantecipacao'))."','$this->vc07_i_diasantecipacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_c_descr"]) || $this->vc07_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,2961,16820,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_c_descr'))."','$this->vc07_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_situacao"]) || $this->vc07_i_situacao != "")
           $resac = db_query("insert into db_acount values($acount,2961,16822,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_situacao'))."','$this->vc07_i_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_sexo"]) || $this->vc07_i_sexo != "")
           $resac = db_query("insert into db_acount values($acount,2961,16823,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_sexo'))."','$this->vc07_i_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_tipocalculo"]) || $this->vc07_i_tipocalculo != "")
           $resac = db_query("insert into db_acount values($acount,2961,16824,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_tipocalculo'))."','$this->vc07_i_tipocalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_diasatraso"]) || $this->vc07_i_diasatraso != "")
           $resac = db_query("insert into db_acount values($acount,2961,16826,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_diasatraso'))."','$this->vc07_i_diasatraso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixainidias"]) || $this->vc07_i_faixainidias != "")
           $resac = db_query("insert into db_acount values($acount,2961,16828,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_faixainidias'))."','$this->vc07_i_faixainidias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixainimes"]) || $this->vc07_i_faixainimes != "")
           $resac = db_query("insert into db_acount values($acount,2961,16829,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_faixainimes'))."','$this->vc07_i_faixainimes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixainiano"]) || $this->vc07_i_faixainiano != "")
           $resac = db_query("insert into db_acount values($acount,2961,16830,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_faixainiano'))."','$this->vc07_i_faixainiano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixafimdias"]) || $this->vc07_i_faixafimdias != "")
           $resac = db_query("insert into db_acount values($acount,2961,16831,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_faixafimdias'))."','$this->vc07_i_faixafimdias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixafimmes"]) || $this->vc07_i_faixafimmes != "")
           $resac = db_query("insert into db_acount values($acount,2961,16832,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_faixafimmes'))."','$this->vc07_i_faixafimmes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc07_i_faixafimano"]) || $this->vc07_i_faixafimano != "")
           $resac = db_query("insert into db_acount values($acount,2961,16833,'".AddSlashes(pg_result($resaco,$conresaco,'vc07_i_faixafimano'))."','$this->vc07_i_faixafimano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vacina Dose nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc07_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vacina Dose nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc07_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc07_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc07_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc07_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16814,'$vc07_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2961,16814,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16815,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_diasvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16816,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_vacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16817,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_dose'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16818,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16819,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16821,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_n_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16827,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_diasantecipacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16820,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16822,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16823,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16824,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_tipocalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16826,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_diasatraso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16828,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_faixainidias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16829,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_faixainimes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16830,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_faixainiano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16831,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_faixafimdias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16832,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_faixafimmes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2961,16833,'','".AddSlashes(pg_result($resaco,$iresaco,'vc07_i_faixafimano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_vacinadose
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc07_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc07_i_codigo = $vc07_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vacina Dose nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc07_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vacina Dose nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc07_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc07_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_vacinadose";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc07_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_vacinadose ";
     $sql .= "      inner join vac_dose  on  vac_dose.vc03_i_codigo = vac_vacinadose.vc07_i_dose";
     $sql .= "      inner join vac_calendario  on  vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario";
     $sql .= "      inner join vac_vacina  on  vac_vacina.vc06_i_codigo = vac_vacinadose.vc07_i_vacina";
     $sql .= "      inner join vac_tipovacina  on  vac_tipovacina.vc04_i_codigo = vac_vacina.vc06_i_tipovacina";
     $sql2 = "";
     if($dbwhere==""){
       if($vc07_i_codigo!=null ){
         $sql2 .= " where vac_vacinadose.vc07_i_codigo = $vc07_i_codigo "; 
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
   function sql_query_file ( $vc07_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_vacinadose ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc07_i_codigo!=null ){
         $sql2 .= " where vac_vacinadose.vc07_i_codigo = $vc07_i_codigo "; 
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
   function sql_query2 ( $vc07_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_vacinadose ";
     $sql .= "      inner join vac_dose          on vac_dose.vc03_i_codigo              = vac_vacinadose.vc07_i_dose ";
     $sql .= "      inner join vac_calendario    on vac_calendario.vc05_i_codigo        = vac_vacinadose.vc07_i_calendario ";
     $sql .= "      inner join vac_vacina        on vac_vacina.vc06_i_codigo            = vac_vacinadose.vc07_i_vacina ";
     $sql .= "      inner join vac_tipovacina    on vac_tipovacina.vc04_i_codigo        = vac_vacina.vc06_i_tipovacina ";
     $sql .= "      left  join vac_doseperiodica on vac_doseperiodica.vc14_i_vacinadose = vac_vacinadose.vc07_i_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc07_i_codigo!=null ){
         $sql2 .= " where vac_vacinadose.vc07_i_codigo = $vc07_i_codigo "; 
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