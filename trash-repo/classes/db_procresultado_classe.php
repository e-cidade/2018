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

//MODULO: educa��o
//CLASSE DA ENTIDADE procresultado
class cl_procresultado { 
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
   var $ed43_i_codigo = 0; 
   var $ed43_i_procedimento = 0; 
   var $ed43_i_resultado = 0; 
   var $ed43_i_formaavaliacao = 0; 
   var $ed43_c_minimoaprov = null; 
   var $ed43_c_obtencao = null; 
   var $ed43_c_geraresultado = null; 
   var $ed43_c_boletim = null; 
   var $ed43_c_reprovafreq = null; 
   var $ed43_c_arredmedia = null; 
   var $ed43_i_sequencia = 0; 
   var $ed43_c_tipoarred = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed43_i_codigo = int8 = C�digo 
                 ed43_i_procedimento = int8 = Procedimento de Avalia��o 
                 ed43_i_resultado = int8 = Resultado 
                 ed43_i_formaavaliacao = int8 = Forma de Avalia��o 
                 ed43_c_minimoaprov = char(10) = M�nimo para Aprova��o 
                 ed43_c_obtencao = char(15) = Forma de Obten��o 
                 ed43_c_geraresultado = char(1) = Gera Resultado Final 
                 ed43_c_boletim = char(1) = Aparece no Boletim 
                 ed43_c_reprovafreq = char(1) = Reprova por Frequ�ncia 
                 ed43_c_arredmedia = char(1) = Arredondar M�dia 
                 ed43_i_sequencia = int4 = Ordena��o 
                 ed43_c_tipoarred = char(1) = Tipo de arredondamento 
                 ";
   //funcao construtor da classe 
   function cl_procresultado() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procresultado"); 
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
       $this->ed43_i_codigo = ($this->ed43_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_i_codigo"]:$this->ed43_i_codigo);
       $this->ed43_i_procedimento = ($this->ed43_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_i_procedimento"]:$this->ed43_i_procedimento);
       $this->ed43_i_resultado = ($this->ed43_i_resultado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_i_resultado"]:$this->ed43_i_resultado);
       $this->ed43_i_formaavaliacao = ($this->ed43_i_formaavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_i_formaavaliacao"]:$this->ed43_i_formaavaliacao);
       $this->ed43_c_minimoaprov = ($this->ed43_c_minimoaprov == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_c_minimoaprov"]:$this->ed43_c_minimoaprov);
       $this->ed43_c_obtencao = ($this->ed43_c_obtencao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_c_obtencao"]:$this->ed43_c_obtencao);
       $this->ed43_c_geraresultado = ($this->ed43_c_geraresultado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_c_geraresultado"]:$this->ed43_c_geraresultado);
       $this->ed43_c_boletim = ($this->ed43_c_boletim == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_c_boletim"]:$this->ed43_c_boletim);
       $this->ed43_c_reprovafreq = ($this->ed43_c_reprovafreq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_c_reprovafreq"]:$this->ed43_c_reprovafreq);
       $this->ed43_c_arredmedia = ($this->ed43_c_arredmedia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_c_arredmedia"]:$this->ed43_c_arredmedia);
       $this->ed43_i_sequencia = ($this->ed43_i_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_i_sequencia"]:$this->ed43_i_sequencia);
       $this->ed43_c_tipoarred = ($this->ed43_c_tipoarred == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_c_tipoarred"]:$this->ed43_c_tipoarred);
     }else{
       $this->ed43_i_codigo = ($this->ed43_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed43_i_codigo"]:$this->ed43_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed43_i_codigo){ 
      $this->atualizacampos();
     if($this->ed43_i_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento de Avalia��o nao Informado.";
       $this->erro_campo = "ed43_i_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed43_i_resultado == null ){ 
       $this->erro_sql = " Campo Resultado nao Informado.";
       $this->erro_campo = "ed43_i_resultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed43_i_formaavaliacao == null ){ 
       $this->erro_sql = " Campo Forma de Avalia��o nao Informado.";
       $this->erro_campo = "ed43_i_formaavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed43_c_minimoaprov == null ){ 
       $this->erro_sql = " Campo M�nimo para Aprova��o nao Informado.";
       $this->erro_campo = "ed43_c_minimoaprov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed43_c_obtencao == null ){ 
       $this->erro_sql = " Campo Forma de Obten��o nao Informado.";
       $this->erro_campo = "ed43_c_obtencao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed43_c_geraresultado == null ){ 
       $this->erro_sql = " Campo Gera Resultado Final nao Informado.";
       $this->erro_campo = "ed43_c_geraresultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed43_c_boletim == null ){ 
       $this->erro_sql = " Campo Aparece no Boletim nao Informado.";
       $this->erro_campo = "ed43_c_boletim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed43_c_reprovafreq == null ){ 
       $this->erro_sql = " Campo Reprova por Frequ�ncia nao Informado.";
       $this->erro_campo = "ed43_c_reprovafreq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed43_c_arredmedia == null ){ 
       $this->erro_sql = " Campo Arredondar M�dia nao Informado.";
       $this->erro_campo = "ed43_c_arredmedia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed43_i_sequencia == null ){ 
       $this->erro_sql = " Campo Ordena��o nao Informado.";
       $this->erro_campo = "ed43_i_sequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed43_c_tipoarred == null ){ 
       $this->erro_sql = " Campo Tipo de arredondamento nao Informado.";
       $this->erro_campo = "ed43_c_tipoarred";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed43_i_codigo == "" || $ed43_i_codigo == null ){
       $result = db_query("select nextval('procresultado_ed43_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procresultado_ed43_i_codigo_seq do campo: ed43_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed43_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from procresultado_ed43_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed43_i_codigo)){
         $this->erro_sql = " Campo ed43_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed43_i_codigo = $ed43_i_codigo; 
       }
     }
     if(($this->ed43_i_codigo == null) || ($this->ed43_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed43_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procresultado(
                                       ed43_i_codigo 
                                      ,ed43_i_procedimento 
                                      ,ed43_i_resultado 
                                      ,ed43_i_formaavaliacao 
                                      ,ed43_c_minimoaprov 
                                      ,ed43_c_obtencao 
                                      ,ed43_c_geraresultado 
                                      ,ed43_c_boletim 
                                      ,ed43_c_reprovafreq 
                                      ,ed43_c_arredmedia 
                                      ,ed43_i_sequencia 
                                      ,ed43_c_tipoarred 
                       )
                values (
                                $this->ed43_i_codigo 
                               ,$this->ed43_i_procedimento 
                               ,$this->ed43_i_resultado 
                               ,$this->ed43_i_formaavaliacao 
                               ,'$this->ed43_c_minimoaprov' 
                               ,'$this->ed43_c_obtencao' 
                               ,'$this->ed43_c_geraresultado' 
                               ,'$this->ed43_c_boletim' 
                               ,'$this->ed43_c_reprovafreq' 
                               ,'$this->ed43_c_arredmedia' 
                               ,$this->ed43_i_sequencia 
                               ,'$this->ed43_c_tipoarred' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Resultados ligados ao Procedimento ($this->ed43_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Resultados ligados ao Procedimento j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Resultados ligados ao Procedimento ($this->ed43_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed43_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed43_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008458,'$this->ed43_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010079,1008458,'','".AddSlashes(pg_result($resaco,0,'ed43_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010079,1008459,'','".AddSlashes(pg_result($resaco,0,'ed43_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010079,1008460,'','".AddSlashes(pg_result($resaco,0,'ed43_i_resultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010079,1008461,'','".AddSlashes(pg_result($resaco,0,'ed43_i_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010079,1008462,'','".AddSlashes(pg_result($resaco,0,'ed43_c_minimoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010079,1008463,'','".AddSlashes(pg_result($resaco,0,'ed43_c_obtencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010079,1008464,'','".AddSlashes(pg_result($resaco,0,'ed43_c_geraresultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010079,1008465,'','".AddSlashes(pg_result($resaco,0,'ed43_c_boletim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010079,1008466,'','".AddSlashes(pg_result($resaco,0,'ed43_c_reprovafreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010079,1008467,'','".AddSlashes(pg_result($resaco,0,'ed43_c_arredmedia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010079,1008468,'','".AddSlashes(pg_result($resaco,0,'ed43_i_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010079,11060,'','".AddSlashes(pg_result($resaco,0,'ed43_c_tipoarred'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed43_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update procresultado set ";
     $virgula = "";
     if(trim($this->ed43_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed43_i_codigo"])){ 
       $sql  .= $virgula." ed43_i_codigo = $this->ed43_i_codigo ";
       $virgula = ",";
       if(trim($this->ed43_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed43_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed43_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed43_i_procedimento"])){ 
       $sql  .= $virgula." ed43_i_procedimento = $this->ed43_i_procedimento ";
       $virgula = ",";
       if(trim($this->ed43_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento de Avalia��o nao Informado.";
         $this->erro_campo = "ed43_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed43_i_resultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed43_i_resultado"])){ 
       $sql  .= $virgula." ed43_i_resultado = $this->ed43_i_resultado ";
       $virgula = ",";
       if(trim($this->ed43_i_resultado) == null ){ 
         $this->erro_sql = " Campo Resultado nao Informado.";
         $this->erro_campo = "ed43_i_resultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed43_i_formaavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed43_i_formaavaliacao"])){ 
       $sql  .= $virgula." ed43_i_formaavaliacao = $this->ed43_i_formaavaliacao ";
       $virgula = ",";
       if(trim($this->ed43_i_formaavaliacao) == null ){ 
         $this->erro_sql = " Campo Forma de Avalia��o nao Informado.";
         $this->erro_campo = "ed43_i_formaavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed43_c_minimoaprov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_minimoaprov"])){ 
       $sql  .= $virgula." ed43_c_minimoaprov = '$this->ed43_c_minimoaprov' ";
       $virgula = ",";
       if(trim($this->ed43_c_minimoaprov) == null ){ 
         $this->erro_sql = " Campo M�nimo para Aprova��o nao Informado.";
         $this->erro_campo = "ed43_c_minimoaprov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed43_c_obtencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_obtencao"])){ 
       $sql  .= $virgula." ed43_c_obtencao = '$this->ed43_c_obtencao' ";
       $virgula = ",";
       if(trim($this->ed43_c_obtencao) == null ){ 
         $this->erro_sql = " Campo Forma de Obten��o nao Informado.";
         $this->erro_campo = "ed43_c_obtencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed43_c_geraresultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_geraresultado"])){ 
       $sql  .= $virgula." ed43_c_geraresultado = '$this->ed43_c_geraresultado' ";
       $virgula = ",";
       if(trim($this->ed43_c_geraresultado) == null ){ 
         $this->erro_sql = " Campo Gera Resultado Final nao Informado.";
         $this->erro_campo = "ed43_c_geraresultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed43_c_boletim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_boletim"])){ 
       $sql  .= $virgula." ed43_c_boletim = '$this->ed43_c_boletim' ";
       $virgula = ",";
       if(trim($this->ed43_c_boletim) == null ){ 
         $this->erro_sql = " Campo Aparece no Boletim nao Informado.";
         $this->erro_campo = "ed43_c_boletim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed43_c_reprovafreq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_reprovafreq"])){ 
       $sql  .= $virgula." ed43_c_reprovafreq = '$this->ed43_c_reprovafreq' ";
       $virgula = ",";
       if(trim($this->ed43_c_reprovafreq) == null ){ 
         $this->erro_sql = " Campo Reprova por Frequ�ncia nao Informado.";
         $this->erro_campo = "ed43_c_reprovafreq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed43_c_arredmedia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_arredmedia"])){ 
       $sql  .= $virgula." ed43_c_arredmedia = '$this->ed43_c_arredmedia' ";
       $virgula = ",";
       if(trim($this->ed43_c_arredmedia) == null ){ 
         $this->erro_sql = " Campo Arredondar M�dia nao Informado.";
         $this->erro_campo = "ed43_c_arredmedia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed43_i_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed43_i_sequencia"])){ 
       $sql  .= $virgula." ed43_i_sequencia = $this->ed43_i_sequencia ";
       $virgula = ",";
       if(trim($this->ed43_i_sequencia) == null ){ 
         $this->erro_sql = " Campo Ordena��o nao Informado.";
         $this->erro_campo = "ed43_i_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed43_c_tipoarred)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_tipoarred"])){ 
       $sql  .= $virgula." ed43_c_tipoarred = '$this->ed43_c_tipoarred' ";
       $virgula = ",";
       if(trim($this->ed43_c_tipoarred) == null ){ 
         $this->erro_sql = " Campo Tipo de arredondamento nao Informado.";
         $this->erro_campo = "ed43_c_tipoarred";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed43_i_codigo!=null){
       $sql .= " ed43_i_codigo = $this->ed43_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed43_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008458,'$this->ed43_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed43_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010079,1008458,'".AddSlashes(pg_result($resaco,$conresaco,'ed43_i_codigo'))."','$this->ed43_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed43_i_procedimento"]))
           $resac = db_query("insert into db_acount values($acount,1010079,1008459,'".AddSlashes(pg_result($resaco,$conresaco,'ed43_i_procedimento'))."','$this->ed43_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed43_i_resultado"]))
           $resac = db_query("insert into db_acount values($acount,1010079,1008460,'".AddSlashes(pg_result($resaco,$conresaco,'ed43_i_resultado'))."','$this->ed43_i_resultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed43_i_formaavaliacao"]))
           $resac = db_query("insert into db_acount values($acount,1010079,1008461,'".AddSlashes(pg_result($resaco,$conresaco,'ed43_i_formaavaliacao'))."','$this->ed43_i_formaavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_minimoaprov"]))
           $resac = db_query("insert into db_acount values($acount,1010079,1008462,'".AddSlashes(pg_result($resaco,$conresaco,'ed43_c_minimoaprov'))."','$this->ed43_c_minimoaprov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_obtencao"]))
           $resac = db_query("insert into db_acount values($acount,1010079,1008463,'".AddSlashes(pg_result($resaco,$conresaco,'ed43_c_obtencao'))."','$this->ed43_c_obtencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_geraresultado"]))
           $resac = db_query("insert into db_acount values($acount,1010079,1008464,'".AddSlashes(pg_result($resaco,$conresaco,'ed43_c_geraresultado'))."','$this->ed43_c_geraresultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_boletim"]))
           $resac = db_query("insert into db_acount values($acount,1010079,1008465,'".AddSlashes(pg_result($resaco,$conresaco,'ed43_c_boletim'))."','$this->ed43_c_boletim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_reprovafreq"]))
           $resac = db_query("insert into db_acount values($acount,1010079,1008466,'".AddSlashes(pg_result($resaco,$conresaco,'ed43_c_reprovafreq'))."','$this->ed43_c_reprovafreq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_arredmedia"]))
           $resac = db_query("insert into db_acount values($acount,1010079,1008467,'".AddSlashes(pg_result($resaco,$conresaco,'ed43_c_arredmedia'))."','$this->ed43_c_arredmedia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed43_i_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1010079,1008468,'".AddSlashes(pg_result($resaco,$conresaco,'ed43_i_sequencia'))."','$this->ed43_i_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed43_c_tipoarred"]))
           $resac = db_query("insert into db_acount values($acount,1010079,11060,'".AddSlashes(pg_result($resaco,$conresaco,'ed43_c_tipoarred'))."','$this->ed43_c_tipoarred',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultados ligados ao Procedimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed43_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Resultados ligados ao Procedimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed43_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed43_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed43_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed43_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008458,'$ed43_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010079,1008458,'','".AddSlashes(pg_result($resaco,$iresaco,'ed43_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010079,1008459,'','".AddSlashes(pg_result($resaco,$iresaco,'ed43_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010079,1008460,'','".AddSlashes(pg_result($resaco,$iresaco,'ed43_i_resultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010079,1008461,'','".AddSlashes(pg_result($resaco,$iresaco,'ed43_i_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010079,1008462,'','".AddSlashes(pg_result($resaco,$iresaco,'ed43_c_minimoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010079,1008463,'','".AddSlashes(pg_result($resaco,$iresaco,'ed43_c_obtencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010079,1008464,'','".AddSlashes(pg_result($resaco,$iresaco,'ed43_c_geraresultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010079,1008465,'','".AddSlashes(pg_result($resaco,$iresaco,'ed43_c_boletim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010079,1008466,'','".AddSlashes(pg_result($resaco,$iresaco,'ed43_c_reprovafreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010079,1008467,'','".AddSlashes(pg_result($resaco,$iresaco,'ed43_c_arredmedia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010079,1008468,'','".AddSlashes(pg_result($resaco,$iresaco,'ed43_i_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010079,11060,'','".AddSlashes(pg_result($resaco,$iresaco,'ed43_c_tipoarred'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procresultado
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed43_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed43_i_codigo = $ed43_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultados ligados ao Procedimento nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed43_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Resultados ligados ao Procedimento nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed43_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed43_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:procresultado";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed43_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from procresultado ";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = procresultado.ed43_i_procedimento";
     $sql .= "      inner join resultado  on  resultado.ed42_i_codigo = procresultado.ed43_i_resultado";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procresultado.ed43_i_formaavaliacao";
     $sql2 = "";
     if($dbwhere==""){
       if($ed43_i_codigo!=null ){
         $sql2 .= " where procresultado.ed43_i_codigo = $ed43_i_codigo ";
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
   function sql_query_aval ( $ed43_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from procresultado ";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = procresultado.ed43_i_procedimento";
     $sql .= "      inner join resultado  on  resultado.ed42_i_codigo = procresultado.ed43_i_resultado";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procresultado.ed43_i_formaavaliacao";
     $sql .= "      inner join avalcompoeres  on  avalcompoeres.ed44_i_procresultado = procresultado.ed43_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed43_i_codigo!=null ){
         $sql2 .= " where procresultado.ed43_i_codigo = $ed43_i_codigo ";
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
   function sql_query_file ( $ed43_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procresultado ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed43_i_codigo!=null ){
         $sql2 .= " where procresultado.ed43_i_codigo = $ed43_i_codigo "; 
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

  function sql_query_procavalres($iCodigo = null, $sCampos = '*', $sOrdem = null, 
                                 $sWhereProcAval = '', $sWhereProcRes = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    
    $sSql .= ' from ';
    $sSql .= '((select ed41_i_sequencia as sequencia, ed41_i_codigo as codigo, 1 as tipo ';
    $sSql .= '  from procavaliacao ';
    if (!empty($sWhereProcAval)) {
      $sSql .= ' where '.$sWhereProcAval;
    }
    $sSql .= ' )';
    $sSql .= '     union ';
    $sSql .= ' (select ed43_i_sequencia as sequencia, ed43_i_codigo as codigo, 2 as tipo ';
    $sSql .= '    from procresultado ';
    if (!empty($sWhereProcRes)) {
      $sSql .= ' where '.$sWhereProcRes;
    }
    $sSql .= ' )) as procavalres ';

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';
      }
    }
    return $sSql;
  }

  /**
   * Busca os elementos que compoe um resultado de avaliacao
   * @param integer $sWhereAvaliacao
   * @param integer $sWhereResultado
   * @return string
   */
  function sql_query_composicaoresultado($iCodigoProcesso) {
  
    $sSql  = " select ed44_i_procavaliacao as elemento, ed44_i_peso as peso, ed44_c_minimoaprov as minimo, ";
    $sSql .= "        ed41_i_sequencia as sequencia, 'A' as tipo_elemento, ed44_c_obrigatorio as obrigatorio";
    $sSql .= "   from avalcompoeres ";
    $sSql .= "  inner join procavaliacao on ed44_i_procavaliacao =  procavaliacao.ed41_i_codigo ";
    if (!empty($iCodigoProcesso)) {
      $sSql .= " where ed44_i_procresultado = {$iCodigoProcesso}";
    }
    $sSql .= "  union ";
    $sSql .= " select ed68_i_procresultcomp as elemento, ed68_i_peso as peso, ed68_c_minimoaprov as minimo, ";
    $sSql .= "        ed43_i_sequencia as sequencia, 'R' as tipo_elemento, '' as obrigatorio ";
    $sSql .= "   from rescompoeres ";
    $sSql .= "  inner join procresultado on procresultado.ed43_i_codigo = rescompoeres.ed68_i_procresultcomp ";
    
    if (!empty($iCodigoProcesso)) {
      $sSql .= " where ed68_i_procresultado = {$iCodigoProcesso}";
    }
    $sSql .= " order by sequencia";
    
    return $sSql;
  }
}
?>